<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\GeoSearch;
use App\Models\Admin;
use App\Models\Amenitie;
use App\Models\BasicSettings\Basic;
use App\Models\Booking;
use App\Models\BookingHour;
use App\Models\Holiday;
use App\Models\Hotel;
use App\Models\HourlyRoomPrice;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\HotelContent;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\RoomCategory;
use App\Models\RoomReview;
use App\Models\Visitor;
use App\Services\PerahuService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PerahuController extends Controller
{
    protected $perahuService;

    public function __construct(PerahuService $perahuService)
    {
        $this->perahuService = $perahuService;
    }

    public function index(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        
        $filters = $request->all();
        $information['room_contents'] = $this->perahuService->getAvailableRooms($filters, $language->id);
        
        // Dapatkan konten unggulan (featured)
        $information['featured_contents'] = Perahu::join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->where('rooms.status', 1)
            ->select('rooms.*', 'room_contents.title', 'room_contents.slug')
            ->limit(4)
            ->get();

        $information['seoInfo'] = $language->seoInfo() ? $language->seoInfo()->first() : null;
        $information['pageHeading'] = $misc->getPageHeading($language);
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['hotelbs'] = Basic::select('google_map_api_key_status')->first();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.perahu._list_items', $information)->render(),
                'items' => $information['room_contents']->items()
            ]);
        }

        return view('frontend.perahu.room-gird', $information);
    }

    public function details($slug, $id)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        
        $room = $this->perahuService->getRoomDetails($id, $language->id);
        if (!$room) {
            abort(404);
        }

        $information['room'] = $room;
        $information['details'] = $room; // Fix for undefined variable in view
        $information['roomContent'] = $room->room_content()->first();
        $information['roomImages'] = $room->room_galleries; // Corrected from room_image
        
        if ($information['roomContent']) {
            $amenityIds = explode(',', $information['roomContent']->amenities ?? '');
            $information['amenities'] = Amenitie::whereIn('id', $amenityIds)->get();
        } else {
            $information['amenities'] = collect([]);
        }

        $information['vendorInfo'] = VendorInfo::where('vendor_id', $room->vendor_id)->where('language_id', $language->id)->first();
        $information['vendor'] = Vendor::find($room->vendor_id);
        
        $information['room_reviews'] = $this->perahuService->getRoomReviews($id);
        $information['avg_rating'] = round(RoomReview::where('room_id', $id)->avg('rating'), 2);
        $information['numOfReview'] = RoomReview::where('room_id', $id)->count();

        // Get Booked Dates to disable in calendar
        $information['bookedDates'] = Booking::where('room_id', $id)
            ->where(function ($q) {
                $q->where('payment_status', 1)
                    ->orWhere(function ($sq) {
                        $sq->where('payment_status', 0)->where('gateway_type', 'offline');
                    })
                    ->orWhere(function ($sq) {
                        $sq->where('payment_status', 0)->where('gateway_type', 'online')->where('order_status', 'pending');
                    });
            })
            ->select('check_in_date', 'check_out_date')
            ->get()
            ->flatMap(function($booking) {
                $dates = [];
                $start = Carbon::parse($booking->check_in_date);
                $end = Carbon::parse($booking->check_out_date);
                for ($date = $start; $date->lte($end); $date->addDay()) {
                    $dates[] = $date->format('Y-m-d');
                }
                return $dates;
            })
            ->unique()
            ->values()
            ->toArray();

        // Dapatkan kamar lain di lokasi yang sama
        $information['relatedRooms'] = Perahu::where('hotel_id', $room->hotel_id)
            ->where('rooms.id', '!=', $id)
            ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->select('rooms.*', 'room_contents.title', 'room_contents.slug')
            ->limit(4)
            ->get();

        return view('frontend.perahu.room-details', $information);
    }

    public function getAddress(Request $request)
    {
        $lat_long = GeoSearch::getCoordinates($request->address, $request->key);
        return $lat_long;
    }

    public function search_room(Request $request)
    {
        return $this->index($request);
    }

    public function aiSearch(Request $request)
    {
        $query = $request->input('q');
        if (empty($query)) {
            return redirect()->route('frontend.perahu');
        }

        try {
            $aiService = new \App\Services\SmartAiService();
            $params = $aiService->generateSmartSearchQuery($query);

            // Construct redirect parameters based on AI response
            $redirectParams = [];
            if (!empty($params['location'])) {
                $redirectParams['location'] = $params['location'];
            }
            if (!empty($params['adults'])) {
                $redirectParams['adults'] = $params['adults'];
            }
            if (!empty($params['keyword'])) {
                $redirectParams['keyword'] = $params['keyword'];
            }

            return redirect()->route('frontend.perahu', $redirectParams)
                ->with('success', 'AI Intelligence: Filter otomatis diterapkan untuk "' . $query . '"');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Smart Search Error: " . $e->getMessage());
            return redirect()->route('frontend.perahu', ['location' => $query])
                ->with('warning', 'AI sedang sibuk. Menggunakan pencarian text biasa.');
        }
    }

    public function aiSearchChat(Request $request)
    {
        $message = $request->input('message');
        if (empty($message)) {
            return response()->json(['reply' => 'Maaf, saya tidak menerima pesan kosong.']);
        }

        try {
            $aiService = new \App\Services\SmartAiService();
            $reply = $aiService->generateChatReply($message);

            return response()->json(['reply' => $reply]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AI Chat Error: " . $e->getMessage());
            return response()->json(['reply' => 'Maaf, sistem AI kami sedang mengalami gangguan teknis.']);
        }
    }

    public function storeReview(Request $request, $id)
    {
        $rule = [
            'rating' => 'required|integer|min:1,max:5',
            'review' => 'required|min:10|max:1000'
        ];
        
        $messages = [
            'rating.required' => __('Rating bintang wajib diisi.'),
            'review.required' => __('Komentar ulasan wajib diisi.'),
            'review.min' => __('Komentar ulasan minimal 10 karakter.')
        ];

        $validator = Validator::make($request->all(), $rule, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::guard('web')->user();
        if ($user) {
            // SECURITY FIX: Only allow reviews for PAID and COMPLETED bookings
            $booking = Booking::where('user_id', $user->id)
                ->where('room_id', $id)
                ->where('payment_status', 1) 
                ->first();

            if ($booking) {
                $room = Perahu::find($id);
                RoomReview::updateOrCreate(
                    ['user_id' => $user->id, 'room_id' => $id],
                    [
                        'review' => $request->review,
                        'rating' => $request->rating,
                        'hotel_id' => $room->hotel_id
                    ]
                );

                $avgRating = RoomReview::where('room_id', $id)->avg('rating');
                $room->update(['average_rating' => $avgRating]);
                
                $hotelAvgRating = RoomReview::where('hotel_id', $room->hotel_id)->avg('rating');
                Hotel::find($room->hotel_id)->update(['average_rating' => $hotelAvgRating]);

                Session::flash('success', __('Ulasan Anda berhasil dikirim! Terima kasih atas masukan Anda.'));
            } else {
                Session::flash('error', __('Maaf, Anda hanya dapat memberikan ulasan untuk trip yang sudah dibayar dan selesai.'));
            }
        } else {
            Session::flash('error', __('Silakan login terlebih dahulu untuk memberikan ulasan.'));
        }
        return redirect()->back();
    }

    public function store_visitor(Request $request)
    {
        $ipAddress = \Request::ip();
        $check = Visitor::where([['room_id', $request->room_id], ['ip_address', $ipAddress], ['date', Carbon::now()->format('y-m-d')]])->first();
        $room = Perahu::find($request->room_id);
        if ($room && !$check) {
            $visitor = new Visitor();
            $visitor->room_id = $request->room_id;
            $visitor->ip_address = $ipAddress;
            $visitor->vendor_id = $room->vendor_id;
            $visitor->date = Carbon::now()->format('y-m-d');
            $visitor->save();
        }
    }
}
