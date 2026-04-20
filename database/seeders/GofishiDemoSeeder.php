<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GofishiDemoSeeder extends Seeder
{
    public function run()
    {
        // 1. Disable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Truncate Tables
        $tables = [
            'vendors', 'vendor_infos',
            'hotels', 'hotel_contents', 'hotel_images', 'hotel_counters', 'hotel_counter_contents',
            'rooms', 'room_contents', 'room_images', 'hourly_room_prices',
            'bookings', 'transactions', 'memberships',
            'blogs', 'blog_informations',
            'user_reviews', 'room_reviews'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->command->info("Truncated table: $table");
        }

        $languages = Language::all();

        // 2.5 Initialize Critical Settings
        if (Schema::hasTable('basic_settings')) {
            DB::table('basic_settings')->truncate();
            DB::table('basic_settings')->insert([
                'uniqid' => 12345,
                'website_title' => 'Gofishi',
                'base_currency_text' => 'IDR',
                'base_currency_symbol' => 'Rp',
                'theme_version' => '1',
                'whatsapp_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (Schema::hasTable('sections')) {
            DB::table('sections')->truncate();
            foreach ($languages as $lang) {
                DB::table('sections')->insert([
                    'language_id' => $lang->id,
                    'footer_section_status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // 3. Clean physical files
        $directories = [
            public_path('assets/img/perahu/featureImage'),
            public_path('assets/img/perahu/room-gallery'),
            public_path('assets/img/hotel/logo'),
            public_path('assets/img/hotel/hotel-gallery'),
            public_path('assets/admin/img/vendor-document')
        ];

        foreach ($directories as $dir) {
            if (File::exists($dir)) {
                File::cleanDirectory($dir);
                $this->command->info("Cleaned directory: $dir");
            }
        }

        // 4. Get Active Languages
        $languages = Language::all();
        if ($languages->isEmpty()) {
            $this->command->error("No languages found in database. Please run language seeder first.");
            return;
        }

        // 5. Create Main Demo Vendor
        $defaultLang = Language::where('is_default', 1)->first() ?? Language::first();
        $vendor = Vendor::create([
            'username' => 'gofishijkt',
            'email' => 'vendor@gofishi.id',
            'password' => Hash::make('password123'),
            'phone' => '628569877491',
            'whatsapp_number' => '628569877491',
            'whatsapp_status' => 1,
            'status' => 1,
            'document_verified' => 1,
            'email_verified_at' => now(),
            'avg_rating' => 5,
            'show_email_addresss' => 1,
            'show_phone_number' => 1,
            'show_contact_form' => 1,
            'vendor_theme_version' => 'light',
            'code' => $defaultLang->code,
            'lang_code' => 'admin_' . $defaultLang->code
        ]);

        foreach ($languages as $lang) {
            VendorInfo::create([
                'vendor_id' => $vendor->id,
                'language_id' => $lang->id,
                'name' => 'Gofishi Jakarta Charter',
                'country' => 'Indonesia',
                'city' => 'Jakarta Utara',
                'address' => 'Kawasan Pesisir Jakarta',
                'details' => 'Penyedia jasa sewa perahu pancing (Saltwater Angling) dan wisata bahari profesional di Jakarta.'
            ]);
        }

        // 6. Create 3 Locations (Hotels)
        $locations = [
            [
                'title' => 'Marina Ancol Hub',
                'lat' => -6.1214,
                'lng' => 106.8302,
                'address' => 'Dermaga 16, Marina Ancol, Jakarta Utara',
                'desc' => 'Pusat keberangkatan kapal pancing sport modern dengan fasilitas lengkap di Jakarta.'
            ],
            [
                'title' => 'Pantai Mutiara Hub',
                'lat' => -6.1089,
                'lng' => 106.7917,
                'address' => 'Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',
                'desc' => 'Dermaga eksklusif untuk layanan sewa yacht dan kapal pancing premium.'
            ],
            [
                'title' => 'Kali Adem Hub',
                'lat' => -6.1016,
                'lng' => 106.7736,
                'address' => 'Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',
                'desc' => 'Akses strategis bagi pemancing harian dengan jangkauan luas ke Kepulauan Seribu.'
            ]
        ];

        foreach ($locations as $locData) {
            $hotel = Hotel::create([
                'vendor_id' => $vendor->id,
                'status' => 1,
                'stars' => 5,
                'average_rating' => 5,
                'latitude' => $locData['lat'],
                'longitude' => $locData['lng']
            ]);

            foreach ($languages as $lang) {
                HotelContent::create([
                    'hotel_id' => $hotel->id,
                    'language_id' => $lang->id,
                    'title' => $locData['title'],
                    'slug' => Str::slug($locData['title']),
                    'address' => $locData['address'],
                    'description' => $locData['desc'],
                    'category_id' => 1
                ]);
            }

            // 7. Create 1 Boat (Room) for each location
            $perahuData = [
                'Marina Ancol Hub' => [
                    'title' => 'Samudra Hunter Sportfishing',
                    'price' => 5000000,
                    'guest' => 10,
                    'engine' => 2,
                    'crew' => 3
                ],
                'Pantai Mutiara Hub' => [
                    'title' => 'Mutiara Azure Luxury Yacht',
                    'price' => 12000000,
                    'guest' => 8,
                    'engine' => 2,
                    'crew' => 4
                ],
                'Kali Adem Hub' => [
                    'title' => 'Angler Express Traditional',
                    'price' => 2500000,
                    'guest' => 12,
                    'engine' => 1,
                    'crew' => 2
                ]
            ];

            $boatInfo = $perahuData[$locData['title']];
            $perahu = Perahu::create([
                'nama_km' => $boatInfo['title'],
                'bedroom_count' => $boatInfo['title'] == 'Mutiara Azure Luxury Yacht' ? 2 : 1,
                'toilet_count' => $boatInfo['title'] == 'Mutiara Azure Luxury Yacht' ? 2 : 1,
                'hotel_id' => $hotel->id,
                'vendor_id' => $vendor->id,
                'status' => 1,
                'adult' => $boatInfo['guest'],
                'bed' => $boatInfo['engine'],
                'bathroom' => $boatInfo['crew'],
                'latitude' => $locData['lat'],
                'longitude' => $locData['lng'],
                'price_day_1' => $boatInfo['price'],
                'average_rating' => 5,
                'number_of_rooms_of_this_same_type' => 1,
                'boat_length' => rand(10, 20),
                'boat_width' => rand(3, 6),
                'crew_count' => $boatInfo['crew'],
                'engine_1' => 'Yamaha 200HP',
                'engine_2' => $boatInfo['engine'] > 1 ? 'Yamaha 200HP' : null,
                'bait' => true,
                'fishing_gear' => true,
                'life_jacket' => true,
                'breakfast' => true,
                'lunch' => true,
                'mineral_water' => true,
                'ac' => $boatInfo['title'] == 'Mutiara Azure Luxury Yacht' ? true : false,
                'electricity' => true,
                'stove' => true,
                'refrigerator' => true,
            ]);

            foreach ($languages as $lang) {
                RoomContent::create([
                    'room_id' => $perahu->id,
                    'language_id' => $lang->id,
                    'room_category' => 1,
                    'title' => $boatInfo['title'],
                    'slug' => Str::slug($boatInfo['title']),
                    'address' => $locData['address'],
                    'description' => "Kapal spesialis pancing laut (Saltwater Angling) siap mengantar Anda mengeksplorasi spot terbaik. Dilengkapi dengan fasilitas pendukung kenyamanan selama perjalanan."
                ]);
            }
        }

        // 8. Re-enable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Gofishi Demo Data Seeded Successfully!');
    }
}
