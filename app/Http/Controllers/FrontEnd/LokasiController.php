<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\GeoSearch;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Booking;
use App\Models\BookingHour;
use App\Models\Holiday;
use App\Models\Hotel;
use App\Models\HotelCategory;
use App\Models\HotelContent;
use App\Models\HotelCounter;
use App\Models\HotelImage;
use App\Models\HourlyRoomPrice;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Perahu;
use App\Models\Room;
use App\Models\RoomContent;
use App\Models\RoomReview;
use App\Models\Amenitie;
use App\Models\FAQ;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\LokasiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class LokasiController extends Controller
{
    protected $lokasiService;

    public function __construct(LokasiService $lokasiService)
    {
        $this->lokasiService = $lokasiService;
    }

    public function getState(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        if ($request->id) {
            $data['states'] = State::where('country_id', $request->id)->get();
            $data['cities'] = City::where('country_id', $request->id)->get();
        } else {
            $data['states'] = State::where('language_id', $language->id)->get();
            $data['cities'] = City::where('language_id', $language->id)->get();
        }

        return $data;
    }

    public function getCity(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        if ($request->id) {
            $data = City::where('state_id', $request->id)->get();
        } else {
            $data = City::where('language_id', $language->id)->get();
        }
        return $data;
    }

    public function index(Request $request)
    {
        $language = get_lang();
        $settings = $this->lokasiService->getSettings();

        $information['seoInfo'] = $language->seoInfo ? $language->seoInfo()->first() : null;
        $information['currencyInfo'] = $this->getCurrencyInfo();
        $information['hotelbs'] = $settings;

        $filters = $request->all();
        $hotels = $this->lokasiService->getAvailableHotels($filters, $language->id);

        $information['currentPageData'] = $hotels;
        $information['hotel_contentss'] = $hotels;
        $information['featured_contents'] = [];
        $information['categories'] = HotelCategory::where('language_id', $language->id)->where('status', 1)
            ->orderBy('serial_number', 'asc')->get();
        if (Schema::hasColumn('booking_hours', 'hour')) {
            $information['bookingHours'] = BookingHour::orderBy('hour', 'desc')->get();
        } else {
            $information['bookingHours'] = collect([]);
        }
        
        $information['countries'] = Country::where('language_id', $language->id)->orderBy('id', 'asc')->get();
        $information['states'] = State::where('language_id', $language->id)->orderBy('id', 'asc')->get();
        $information['cities'] = City::where('language_id', $language->id)->orderBy('id', 'asc')->get();

        $view = $settings->hotel_view;
        if ($view == 0) {
            return view('frontend.lokasi.hotel-map', $information);
        } else {
            return view('frontend.lokasi.hotel-gird', $information);
        }
    }

    public function search_hotel(Request $request)
    {
        return $this->index($request);
    }

    public function details(Request $request, $slug, $id)
    {
        $misc = app(MiscellaneousController::class);
        $language = $misc->getLanguage();
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = $misc->getPageHeading($language);

        $vendorId = Hotel::where('id', $id)->pluck('vendor_id')->first();

        $hotel = HotelContent::join('hotels', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->Join('hotel_categories', 'hotel_contents.category_id', '=', 'hotel_categories.id')
            ->where('hotel_contents.language_id', $language->id)
            ->where('hotel_categories.status', 1)
            ->where('hotels.status', 1)
            ->select(
                'hotels.*',
                'hotel_contents.address as address',
                'hotel_contents.title',
                'hotel_contents.slug',
                'hotel_contents.city_id',
                'hotel_contents.state_id',
                'hotel_contents.country_id',
                'hotel_contents.amenities',
                'hotel_categories.name as categoryName',
                'hotel_categories.slug as categorySlug',
                'hotel_contents.meta_keyword',
                'hotel_contents.meta_description',
                'hotel_contents.description',
            )
            ->where('hotels.id', $id)
            ->firstOrFail();

        if ($vendorId == 0) {
            $information['vendor'] = Admin::first();
            $information['userName'] = 'admin';
        } else {
            $information['vendor'] = Vendor::Where('id', $vendorId)->first();
            $information['userName'] = $information['vendor'] ? $information['vendor']->username : 'Unknown';
        }

        $information['hotel'] = $hotel;
        $information['hotelImages'] = HotelImage::where('hotel_id', $id)->get();
        $information['language'] = $language;

        // Fetch Actual Amenities Objects based on IDs stored in hotel_contents
        $amenityIds = json_decode($hotel->amenities, true) ?? [];
        $information['all_amenities'] = Amenitie::whereIn('id', $amenityIds)
            ->where('language_id', $language->id)
            ->get();

        // FAQs for this location (safe check for language_id)
        $faqsQuery = \App\Models\HotelFaq::where('hotel_id', $id);
        if (\Illuminate\Support\Facades\Schema::hasColumn('hotel_faqs', 'language_id')) {
            $faqsQuery->where('language_id', $language->id);
        }
        $information['faqs'] = $faqsQuery->orderBy('serial_number', 'asc')
            ->get();

        $information['hotelCounters'] = HotelCounter::join('hotel_counter_contents', 'hotel_counters.id', '=', 'hotel_counter_contents.hotel_counter_id')
            ->where('hotel_id', $id)
            ->where('hotel_counter_contents.language_id', $language->id)->get();

        $reviews = RoomReview::with('userInfo')->where('hotel_id', $id)->orderByDesc('id')->get();
        $information['reviews'] = $reviews;
        $information['numOfReview'] = $reviews->count();

        // ---------------------------------------------------------------------
        // FILTER LOGIC FOR BOATS (ROOMS)
        // ---------------------------------------------------------------------
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');
        $guests = $request->input('guests', 1);

        $roomsQuery = RoomContent::join('rooms', 'rooms.id', '=', 'room_contents.room_id')
            ->leftJoin('room_categories', 'room_contents.room_category', '=', 'room_categories.id')
            ->where('rooms.hotel_id', $id)
            ->where('room_contents.language_id', $language->id)
            ->where('rooms.status', 1);

        // Count unique vendors in this location
        $information['total_vendors'] = Room::where('hotel_id', $id)
            ->whereNotNull('vendor_id')
            ->distinct('vendor_id')
            ->count('vendor_id');

        if ($request->filled('guests')) {
            $roomsQuery->where('rooms.adult', '>=', $guests);
        }

        // Availability Filtering Logic
        if ($checkin && $checkout) {
            $checkInDateTime = Carbon::parse($checkin . ' 00:00:00');
            $checkOutDateTime = Carbon::parse($checkout . ' 23:59:59');

            $roomsQuery->whereRaw('rooms.number_of_rooms_of_this_same_type > (
                SELECT COUNT(*) FROM bookings 
                WHERE bookings.room_id = rooms.id 
                AND bookings.payment_status != 2
                AND (
                    (bookings.check_in_date_time BETWEEN ? AND ?)
                    OR (bookings.check_out_date_time BETWEEN ? AND ?)
                    OR (bookings.check_in_date_time <= ? AND bookings.check_out_date_time >= ?)
                )
            )', [
                $checkInDateTime, $checkOutDateTime,
                $checkInDateTime, $checkOutDateTime,
                $checkInDateTime, $checkOutDateTime
            ]);
        }

        $information['rooms'] = $roomsQuery->select(
                'rooms.*',
                'room_contents.title',
                'room_contents.slug',
                'room_categories.name as room_category_name'
            )
            ->orderBy('rooms.id', 'desc')
            ->get();

        return view('frontend.lokasi.hotel-details', $information);
    }
}
