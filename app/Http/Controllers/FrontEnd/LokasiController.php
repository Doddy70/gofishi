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
        $misc = app(MiscellaneousController::class);
        $language = $misc->getLanguage();
        
        $information['seoInfo'] = $language->seoInfo ? $language->seoInfo()->first() : null;
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = __('Jelajahi Semua Dermaga Gofishi');

        // We will use the first/primary hotel as the "Main Hub" presentation
        // Alternately, we could pass an aggregate, but let's select the first one to render the nice Single layout.
        $hotel = HotelContent::join('hotels', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->leftJoin('hotel_categories', 'hotel_contents.category_id', '=', 'hotel_categories.id')
            ->where('hotel_contents.language_id', $language->id)
            ->where('hotels.status', 1)
            ->select('hotels.*', 'hotel_contents.address as address', 'hotel_contents.title', 'hotel_contents.slug', 'hotel_contents.description', 'hotel_contents.amenities')
            ->first();
            
        if (!$hotel) {
            return redirect()->route('index');
        }
        
        // Mock a title if it's supposed to represent "All Hubs" 
        $hotel->title = "Pusat Keberangkatan Gofishi";
        $hotel->address = "Pelabuhan Premium Jakarta";

        $information['hotel'] = $hotel;
        $information['hotelImages'] = HotelImage::limit(8)->get(); // General gallery
        $information['language'] = $language;
        $information['all_amenities'] = Amenitie::where('language_id', $language->id)->get();
        $information['faqs'] = FAQ::where('language_id', $language->id)->take(5)->get(); // general FAQs
        $information['numOfReview'] = RoomReview::count();
        $information['total_vendors'] = Vendor::count();
        
        // Fetch all specific locations (Hotels/Hubs) for the user to select from
        $information['all_hotels'] = \App\Models\Hotel::join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->where('hotel_contents.language_id', $language->id)
            ->where('hotels.status', 1)
            ->select('hotels.*', 'hotel_contents.title', 'hotel_contents.slug', 'hotel_contents.address')
            ->orderBy('hotels.id', 'desc')
            ->get();

        // ---------------------------------------------------------------------
        // FETCH ALL BOATS FOR THE "ALL LOCATIONS" SINGLE PAGE
        // ---------------------------------------------------------------------
        $roomsQuery = \App\Models\Perahu::join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->where('rooms.status', 1);

        $information['rooms'] = $roomsQuery->select(
                'rooms.*',
                'room_contents.title',
                'room_contents.slug'
            )
            ->orderBy('rooms.average_rating', 'desc')
            ->get()
            ->loadMissing(['room_galleries', 'hotel.hotel_contents', 'room_content']);

        // Render the beautiful Single Layout for the main /lokasi page!
        return view('frontend.lokasi.hotel-details', $information);
    }

    public function search_hotel(Request $request)
    {
        return $this->index($request);
    }

    public function details(Request $request, $slug, $id)
    {
        $misc = app(MiscellaneousController::class);
        $language = $misc->getLanguage();
        
        $information['seoInfo'] = $language->seoInfo ? $language->seoInfo()->first() : null;
        $information['pageHeading'] = $misc->getPageHeading($language);
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['hotelbs'] = Basic::select('google_map_api_key_status')->first();

        // FETCH THE HOTEL DETAILS TO PASS TO THE GRID PAGE SO IT KNOWS WHICH HUB IT IS
        $hotel = HotelContent::where('hotel_id', $id)
                    ->where('language_id', $language->id)
                    ->first();
                    
        $information['hub'] = $hotel; // Custom variable so we can display it on the map header

        // ---------------------------------------------------------------------
        // FETCH BOATS FOR THIS SPECIFIC LOCATION, RENDER AS SPLIT-VIEW MAP (room-gird)
        // ---------------------------------------------------------------------
        $roomsQuery = \App\Models\Perahu::join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->leftJoin('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->leftJoin('hotel_categories', 'hotels.id', '=', 'hotel_categories.id')
            ->where('rooms.hotel_id', $id)
            ->where('room_contents.language_id', $language->id)
            ->where('rooms.status', 1);

        $information['room_contents'] = $roomsQuery->select(
                'rooms.*',
                'room_contents.title',
                'room_contents.slug',
                'rooms.latitude as room_lat',
                'rooms.longitude as room_lng',
                'hotels.latitude',
                'hotels.longitude'
            )
            ->orderBy('rooms.id', 'desc')
            ->paginate(12);

        // Fetch categories to pass to view if room-gird needs it
        $information['roomCategories'] = \App\Models\RoomCategory::where('language_id', $language->id)->where('status', 1)->get();
        // Min max price 
        $information['min'] = Room::min('price_day_1') ?? 0;
        $information['max'] = Room::max('price_day_1') ?? 100000000;
        
        if ($request->ajax()) {
            // Need to return json if the room-gird map triggers ajax filters
            return response()->json([
                'html' => view('frontend.perahu._list_items', $information)->render(),
                'items' => $information['room_contents']->items(),
                'pagination' => (string) $information['room_contents']->links('pagination::tailwind')
            ]);
        }

        // Return the Map / Grid View so the user can search boats in this exact location!
        return view('frontend.perahu.room-gird', $information);
    }
}
