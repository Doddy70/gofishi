<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\AboutUs;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Banner;
use App\Models\HomePage\CustomSection;
use App\Models\HomePage\Feature;
use App\Models\HomePage\Section;
use App\Models\HomePage\SectionContent;
use App\Models\Journal\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Package;
use App\Models\RoomContent;
use App\Models\Perahu;
use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use App\Models\RoomCategory;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    if (!$language) {
        $language = Language::where('is_default', 1)->first() ?? Language::first();
    }

    if ($language) {
        $information['seoInfo'] = $language->seoInfo() ? $language->seoInfo()->first() : null;
    } else {
        $information['seoInfo'] = null;
    }
    $information['pageHeading'] = $language ? $misc->getPageHeading($language) : null;

    $information['sectionContent'] = $language ? SectionContent::where('language_id', $language->id)->first() : null;
    
    $information['images'] = null;
    if (Schema::hasTable('basic_settings')) {
        try {
            $information['images'] = DB::table('basic_settings')->select('hero_section_image', 'about_section_image', 'feature_section_image', 'counter_section_image', 'call_to_action_section_image', 'call_to_action_section_inner_image', 'testimonial_section_image')->first();
        } catch (\Exception $e) {}
    }

    // Data Section & Status
    $information['secInfo'] = null;
    $information['homecusSec'] = [];
    if (Schema::hasTable('sections')) {
        try {
            $information['secInfo'] = Section::query()->first();
            $information['homecusSec'] = json_decode($information['secInfo']->custom_section_status ?? '{}', true) ?? [];
        } catch (\Exception $e) {}
    }
    
    // Inisialisasi variabel section order agar tidak null
    $orderTypes = ['after_hero', 'after_city', 'after_featured', 'after_testimonial', 'after_call_to_action', 'after_blog', 'after_benifit', 'after_featured_room'];
    foreach ($orderTypes as $type) {
        if (Schema::hasTable('custom_sections')) {
            $information[$type] = DB::table('custom_sections')->where('page_type', $type)->orderBy('serial_number', 'ASC')->get();
        } else {
            $information[$type] = collect([]);
        }
    }

    // Ambil data untuk home-v3 (Hero Search, dsb)
    $information['categories'] = RoomCategory::where('language_id', $language->id)->where('status', 1)
        ->orderBy('serial_number', 'asc')->get();

    // Data Kota / City (Lokasi)
    $information['cities'] = City::where('language_id', $language->id)->get();

    // Data Kategori Lokasi (Dermaga)
    $information['location_categories'] = \App\Models\HotelCategory::where('language_id', $language->id)
        ->where('status', 1)
        ->orderBy('serial_number', 'asc')
        ->limit(12)
        ->get();

    // Data Fasilitas (Amenities)
    $information['all_amenities'] = \App\Models\Amenitie::where('language_id', $language->id)
        ->orderBy('id', 'asc')
        ->limit(12)
        ->get();

    // Data Fitur / Keunggulan
    $information['features'] = Feature::where('language_id', $language->id)->orderBy('serial_number', 'ASC')->get();

    // Data Testimonial
    $information['testimonials'] = DB::table('testimonials')->where('language_id', $language->id)->orderBy('id', 'ASC')->get();

    // Data Blog / Tips Wisata
    $information['blogs'] = Blog::join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
        ->where('blog_informations.language_id', $language->id)
        ->select('blogs.*', 'blog_informations.title', 'blog_informations.slug', 'blog_informations.content', 'blog_informations.author')
        ->orderBy('blogs.id', 'desc')
        ->limit(3)
        ->get();
    $information['blog_count'] = $information['blogs'];

    // Data Perahu Terbaru - Start from Perahu model to ensure correct object type
    $information['room_contents'] = Perahu::join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
      ->join('hotels', 'hotels.id', '=', 'rooms.hotel_id')
      ->join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
      ->where('room_contents.language_id', $language->id)
      ->where('hotel_contents.language_id', $language->id)
      ->where('rooms.status', 1)
      ->select(
          'rooms.*', 
          'room_contents.title', 
          'room_contents.slug', 
          'hotel_contents.slug as hotelSlug', 
          'hotels.id as hotelId',
          'hotel_contents.title as hotelName'
      )
      ->orderBy('rooms.id', 'desc')
      ->limit(8)
      ->get()
      ->loadMissing(['room_galleries', 'hotel.hotel_contents', 'room_content']);
    $information['room_contents_count'] = $information['room_contents'];

    // Manfaat / Benefits
    $information['benifits'] = DB::table('benifits')->where('language_id', $language->id)->orderBy('id', 'ASC')->get();

    // Hero Search Data
    $information['max_price'] = DB::table('rooms')->max('price_day_1') ?? 10000000;
    $information['min_price'] = DB::table('rooms')->min('price_day_1') ?? 0;

    return view('frontend.home.index-v3', $information);
  }

  public function about()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $information['pageHeading'] = $misc->getPageHeading($language);
    $information['seoInfo'] = $language->seoInfo() ? $language->seoInfo()->first() : null;
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['content'] = AboutUs::where('language_id', $language->id)->first();

    return view('frontend.about-us', $information);
  }

  public function offline()
  {
    return view('frontend.offline');
  }
}
