<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\VendorPermissionHelper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use Illuminate\Http\Request;
use Purifier;
use Illuminate\Support\Facades\Auth;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\PaymentGateway\OfflineGateway;
use App\Http\Requests\Room\RoomStoreRequest;
use App\Http\Requests\Room\RoomUpdateRequest;
use App\Models\AdditionalService;
use App\Models\AdditionalServiceContent;
use App\Models\BookingHour;
use App\Models\FeaturedRoomCharge;
use App\Models\Hotel;
use App\Models\HotelCounter;
use App\Models\HourlyRoomPrice;
use App\Models\Perahu;
use App\Models\RoomCategory;
use App\Models\RoomContent;
use App\Models\RoomFeature;
use App\Models\RoomImage;
use App\Models\RoomReview;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class PerahuController extends Controller
{
    public function index(Request $request)
    {
        $information['currencyInfo'] = $this->getCurrencyInfo();
        $information['langs'] = Language::all();
        $midtransGateway = OnlineGateway::whereKeyword('midtrans')->first();
        $midtrans = $midtransGateway ? json_decode($midtransGateway->information, true) : [];
        if (!isset($midtrans['midtrans_mode'])) {
            $midtrans['midtrans_mode'] = isset($midtrans['sandbox_check']) ? (int)$midtrans['sandbox_check'] : 1;
        }
        $information['midtrans'] = $midtrans;


        $language =  Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;


        $language_id = $language->id;
        $status = $title = $roomCategories  = $featured =  null;

        if (request()->filled('status')) {
            $status = $request->status;
        }

        $type_roomIds = [];
        if (request()->filled('roomCategories') && request()->input('roomCategories') !== "All") {
            $roomCategories = $request->roomCategories;
            $type_content = RoomCategory::where([['language_id', $language->id], ['name', $roomCategories]])->first();

            if (!empty($type_content)) {
                $category = $type_content->id;
                $contents = RoomContent::where('language_id', $language->id)
                    ->where('room_category', $category)
                    ->get()
                    ->pluck('room_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $type_roomIds)) {
                        array_push($type_roomIds, $content);
                    }
                }
            }
        }

        $featured_roomIds = [];
        if ($request->filled('featured') && $request->input('featured') !== "All") {
            $featured = $request->input('featured');

            if ($featured == 'active') {
                $contents = RoomFeature::where('order_status', '=', 'apporved')
                    ->where('payment_status', '=', 'completed')
                    ->whereDate('end_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->get()
                    ->pluck('room_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $featured_roomIds)) {
                        array_push($featured_roomIds, $content);
                    }
                }
            }
            if ($featured == 'pending') {
                $contents = RoomFeature::where('order_status', '=', 'pending')
                    ->get()
                    ->pluck('room_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $featured_roomIds)) {
                        array_push($featured_roomIds, $content);
                    }
                }
            }
            if ($featured == 'unfeatured') {
                $contents = RoomFeature::where('order_status', '=', 'pending')
                    ->get()
                    ->pluck('room_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $featured_roomIds)) {
                        array_push($featured_roomIds, $content);
                    }
                }
                $contentss = RoomFeature::where('order_status', '=', 'apporved')
                    ->where('payment_status', '=', 'completed')
                    ->whereDate('end_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->get()
                    ->pluck('room_id');
                foreach ($contentss as $conten) {
                    if (!in_array($conten, $featured_roomIds)) {
                        array_push($featured_roomIds, $conten);
                    }
                }
            }
        }

        if (request()->filled('vendor_id') && request()->input('vendor_id') !== "All") {
            $vendor_id = request()->input('vendor_id');
        }

        $roomIds = [];
        if ($request->filled('title')) {
            $title = $request->title;
            $room_contents = RoomContent::where('language_id', $language->id)
                ->where('title', 'like', '%' . $title . '%')
                ->get()
                ->pluck('room_id');
            foreach ($room_contents as $room_content) {
                if (!in_array($room_content, $roomIds)) {
                    array_push($roomIds, $room_content);
                }
            }
        }

        $information['rooms'] = Perahu::with([
            'room_content' => function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            },
            'vendor'
        ])
            ->when($roomCategories, function ($query) use ($type_roomIds) {
                return $query->whereIn('rooms.id', $type_roomIds);
            })

            ->when($featured, function ($query) use ($featured_roomIds, $featured) {
                if ($featured !== 'unfeatured') {
                    return $query->whereIn('rooms.id', $featured_roomIds);
                } else {
                    return $query->whereNotIn('rooms.id', $featured_roomIds);
                }
            })

            ->when($title, function ($query) use ($roomIds) {
                return $query->whereIn('rooms.id', $roomIds);
            })
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $information['roomCategories'] = RoomCategory::Where('language_id', $language_id)->get();

        //Feature part
        $information['onlineGateways'] = OnlineGateway::where('status', 1)->get();

        $information['offline_gateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

        $stripe = OnlineGateway::where('keyword', 'stripe')->first();
        $stripe_info = json_decode($stripe->information, true);
        $information['stripe_key'] = $stripe_info['key'];

        $authorizenet = OnlineGateway::query()->whereKeyword('authorize.net')->first();
        $anetInfo = json_decode($authorizenet->information);

        if ($anetInfo->sandbox_check == 1) {
            $information['anetSource'] = 'https://jstest.authorize.net/v1/Accept.js';
        } else {
            $information['anetSource'] = 'https://js.authorize.net/v1/Accept.js';
        }

        $information['anetClientKey'] = $anetInfo->public_key;
        $information['anetLoginId'] = $anetInfo->login_id;



        $charges = FeaturedRoomCharge::orderBy('days')->get();
        $information['charges'] = $charges;
        return view('vendors.perahu.index', $information);
    }

    public function updateStatus(Request $request)
    {

        $vendorId = Auth::guard('vendor')->user()->id;
        $current_package = VendorPermissionHelper::packagePermission($vendorId);

        if ($current_package != '[]') {

            $room = Perahu::findOrFail($request->roomId);

            if ($request->status == 1) {
                $room->update(['status' => 1]);

                Session::flash('success', __('Perahu Active successfully') . '!');
            }
            if ($request->status == 0) {
                $room->update(['status' => 0]);

                Session::flash('success', __('Perahu Deactive successfully') . '!');
            }

            return redirect()->back();
        } else {

            Session::flash('success', __('Please Buy a plan to manage Hide/Show') . '!');
            return redirect()->route('vendor.perahu_management.perahu');
        }
    }

    public function create()
    {
        $information = [];
        $languages = Language::get();
        $information['languages'] = $languages;
        $information['bookingHours']  = BookingHour::orderBy('serial_number', 'asc')->get();
        $language = Language::where('is_default', 1)->first();
        $language_id = $language->id;

        $information['hotels'] = Hotel::with([
            'hotel_contents' => function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            },
        ])
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->get();


        return view('vendors.perahu.create', $information);
    }
    public function imagesstore(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg', 'webp');
        $rules = [
            'file' => [
                'max:1024',
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = uniqid() . '.jpg';

        $directory = public_path('assets/img/room/room-gallery/');
        @mkdir($directory, 0775, true);
        $img->move($directory, $filename);

        $pi = new RoomImage();
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }
    public function imagermv(Request $request)
    {
        $pi = RoomImage::findOrFail($request->fileid);
        @unlink(public_path('assets/img/room/room-gallery/') . $pi->image);
        $pi->delete();
        return $pi->id;
    }
    public function imagedbrmv(Request $request)
    {
        $pi = RoomImage::findOrFail($request->fileid);
        $image_count = RoomImage::where('room_id', $pi->room_id)->get()->count();
        if ($image_count > 1) {
            @unlink(public_path('assets/img/room/room-gallery/') . $pi->image);
            $pi->delete();

            Session::flash('success', __('Slider image deleted successfully') . '!');
            return Response::json(['status' => 'success'], 200);
        } else {
            Session::flash('warning', __("You can't delete all images") . '!');
            return Response::json(['status' => 'success'], 200);
        }
    }
    public function getState(Request $request)
    {
        $data['states'] = State::where('country_id', $request->id)->get();
        $data['cities'] = City::where('country_id', $request->id)->get();
        return $data;
    }
    public function getCity(Request $request)
    {
        $data = City::where('state_id', $request->id)->get();
        return $data;
    }
    public function store(RoomStoreRequest $request, \App\Actions\Room\CreateRoomAction $createRoom)
    {
        $vendorId = Auth::guard('vendor')->id();
        $current_package = VendorPermissionHelper::packagePermission($vendorId);

        if ($current_package == '[]') {
            Session::flash('success', __('Please buy a plan to add a perahu') . '!');
            return Response::json(['status' => 'success'], 200);
        }

        if (vendorTotalAddedRoom($vendorId) >= $current_package->number_of_room) {
            Session::flash('success', __('Perahu limit reached or exceeded') . '!');
            return Response::json(['status' => 'success'], 200);
        }

        $createRoom($request->validated());

        Session::flash('success', __('New Perahu added successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }

    public function update(RoomUpdateRequest $request, $id, \App\Actions\Room\UpdateRoomAction $updateRoom)
    {
        $room = Perahu::findOrFail($id);

        $updateRoom($room, $request->validated());

        Session::flash('success', __('Perahu Updated successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }

    public function delete($id, \App\Actions\Room\DeleteRoomAction $deleteRoom)
    {
        $room = Perahu::findOrFail($id);
        $deleteRoom($room);

        Session::flash('success', __('Perahu deleted successfully') . '!');
        return redirect()->back();
    }

    public function bulkDelete(Request $request, \App\Actions\Room\DeleteRoomAction $deleteRoom)
    {
        $ids = $request->ids ?? [];

        foreach ($ids as $id) {
            $room = Perahu::find($id);
            if ($room) {
                $deleteRoom($room);
            }
        }

        Session::flash('success', __('Perahu deleted successfully') . '!');
        return response()->json(['status' => 'success'], 200);
    }
    public function edit($id)
    {
        $vendorId = Auth::guard('vendor')->user()->id;
        $current_package = VendorPermissionHelper::packagePermission($vendorId);
        $defaultLang = Language::query()->where('is_default', 1)->first();

        if ($current_package && (is_object($current_package) || $current_package->isNotEmpty())) {
            $information['room'] = Perahu::with('room_galleries')->where('vendor_id', '=', $vendorId)->findOrFail($id);
            $information['languages'] = Language::all();

            $information['room_content'] = RoomContent::where([
                ['language_id', $defaultLang->id],
                ['room_id', $id]
            ])->first();

            $language = Language::where('is_default', 1)->first();
            $language_id = $language->id;

            $information['hotels'] = Hotel::with([
                'hotel_contents' => function ($q) use ($language_id) {
                    $q->where('language_id', $language_id);
                },
            ])
                ->where('vendor_id', $vendorId)
                ->orderBy('id', 'desc')
                ->select('id')
                ->get();
            $information['categories'] = RoomCategory::where('language_id', $language_id)->get();

            return view('vendors.perahu.edit', $information);
        } else {
            Session::flash('success', __('Please Buy a plan to edit perahu') . '!');
            return redirect()->route('vendor.perahu_management.perahu');
        }
    }

    public function manageAdditionalService($id)
    {
        $vendorId = Auth::guard('vendor')->user()->id;
        Perahu::where([['id', $id], ['vendor_id', $vendorId]])->firstOrFail();

        $information['perahu_id'] = $id;
        $information['languages'] = Language::all();
        $information['additional_services'] = AdditionalService::where('room_id', $id)->get();
        $information['defaultLang'] = Language::where('is_default', 1)->first();

        return view('vendors.perahu.additional_services', $information);
    }

    public function updateAdditionalService(Request $request, $id)
    {
        $languages = Language::all();
        $vendorId = Auth::guard('vendor')->user()->id;
        Perahu::where([['id', $id], ['vendor_id', $vendorId]])->firstOrFail();

        foreach ($languages as $language) {
            if (!empty($request[$language->code . '_name'])) {
                $name_datas = $request[$language->code . '_name'];
                foreach ($name_datas as $key => $data) {
                    $additional_service = AdditionalService::where([['room_id', $id], ['key', $key]])->first();
                    if (is_null($additional_service)) {
                        $additional_service = new AdditionalService();
                        $additional_service->room_id = $id;
                        $additional_service->key = $key;
                        $additional_service->save();
                    }
                    $additional_service_content = AdditionalServiceContent::where([['additional_service_id', $additional_service->id], ['language_id', $language->id]])->first();
                    if (is_null($additional_service_content)) {
                        $additional_service_content = new AdditionalServiceContent();
                        $additional_service_content->additional_service_id = $additional_service->id;
                        $additional_service_content->language_id = $language->id;
                    }
                    $additional_service_content->name = $data;
                    $additional_service_content->amount = $request[$language->code . '_amount'][$key];
                    $additional_service_content->save();
                }
            }
        }

        Session::flash('success', __('Additional Service updated successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }

    public function amenitiesUpdate(Request $request)
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            $room_content = RoomContent::where([['room_id', $request->roomId], ['language_id', $language->id]])->first();
            if ($room_content) {
                if ($request->amenities) {
                    $room_content->update([
                        'amenities' => json_encode($request->amenities)
                    ]);
                } else {
                    $room_content->update([
                        'amenities' => null
                    ]);
                }
            }
        }

        Session::flash('success', __('Amenities updated successfully') . '!');
        return redirect()->back();
    }
}
