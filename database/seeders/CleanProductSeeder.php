<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\HotelCounter;
use App\Models\HotelCounterContent;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\Language;
use App\Models\User;
use App\Models\RoomReview;
use App\Models\Booking;
use App\Models\HotelCategory;
use App\Models\RoomCategory;
use App\Models\AdditionalService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CleanProductSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data produk lama (Lokasi, Perahu, Booking, Review)
        $tables = [
            'hotels', 'hotel_contents', 'hotel_images',
            'hotel_counters', 'hotel_counter_contents',
            'rooms', 'room_contents', 'room_images', 'room_features',
            'bookings', 'room_reviews', 'user_reviews',
            'hourly_room_prices',
        ];
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("Truncated: $table");
            }
        }

        // Ambil language ID
        $idLang = Language::where('code', 'id')->first()->id ?? 1;
        $enLang = Language::where('code', 'en')->first()->id ?? 2;
        $languages = Language::whereIn('code', ['id', 'en'])->get();

        // Pastikan ada vendor utama
        $vendor = Vendor::first();
        if (!$vendor) {
            $defaultLang = Language::where('is_default', 1)->first() ?? Language::first();
            $vendor = Vendor::create([
                'username'          => 'gofishijkt',
                'email'             => 'vendor@gofishi.id',
                'password'          => Hash::make('password123'),
                'phone'             => '628569877491',
                'whatsapp_number'   => '628569877491',
                'whatsapp_status'   => 1,
                'status'            => 1,
                'document_verified' => 1,
                'email_verified_at' => now(),
                'avg_rating'        => 5,
                'code'              => $defaultLang->code,
                'lang_code'         => 'admin_' . $defaultLang->code,
            ]);
            foreach ($languages as $lang) {
                VendorInfo::create([
                    'vendor_id'   => $vendor->id,
                    'language_id' => $lang->id,
                    'name'        => 'Gofishi Jakarta Charter',
                    'country'     => 'Indonesia',
                    'city'        => 'Jakarta Utara',
                    'details'     => 'Penyedia jasa sewa perahu pancing profesional di Jakarta.',
                ]);
            }
        }

        // Pastikan ada kategori
        $hCatId = HotelCategory::where('language_id', $idLang)->first()->id ?? null;
        if (!$hCatId) {
            $hCatId = HotelCategory::create(['language_id' => $idLang, 'name' => 'Dermaga Jakarta', 'status' => 1, 'serial_number' => 1])->id;
        }
        $rCatId = RoomCategory::where('language_id', $idLang)->first()->id ?? null;
        if (!$rCatId) {
            $rCatId = RoomCategory::create(['language_id' => $idLang, 'name' => 'Sportfishing Boat', 'status' => 1, 'serial_number' => 1])->id;
        }

        // =====================================================
        // 1. Buat 3 Lokasi (Dermaga)
        // =====================================================
        $hubs = [
            [
                'title' => 'Marina Ancol Hub',
                'lat'   => -6.1214, 'lng' => 106.8302,
                'addr'  => 'Dermaga 16 Marina Ancol, Jakarta Utara',
                'desc'  => 'Pusat keberangkatan kapal pancing sport modern dengan fasilitas dermaga lengkap dan parkir luas di kawasan rekreasi Ancol.',
            ],
            [
                'title' => 'Pantai Mutiara Hub',
                'lat'   => -6.1089, 'lng' => 106.7917,
                'addr'  => 'Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',
                'desc'  => 'Dermaga premium untuk layanan yacht dan kapal pancing kelas atas. Akses eksklusif dan pemandangan terbaik teluk Jakarta.',
            ],
            [
                'title' => 'Muara Angke Hub',
                'lat'   => -6.1016, 'lng' => 106.7736,
                'addr'  => 'Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',
                'desc'  => 'Dermaga strategis dengan akses luas ke perairan Kepulauan Seribu. Pilihan terbaik untuk trip harian dan paket hemat.',
            ],
        ];

        $hotelIds = [];
        foreach ($hubs as $hub) {
            $hotel = Hotel::create([
                'vendor_id'      => $vendor->id,
                'status'         => 1,
                'stars'          => 5,
                'average_rating' => 5,
                'latitude'       => $hub['lat'],
                'longitude'      => $hub['lng'],
            ]);
            $hotelIds[] = $hotel->id;

            // Konten lokasi untuk setiap bahasa
            foreach ($languages as $lang) {
                HotelContent::create([
                    'hotel_id'    => $hotel->id,
                    'language_id' => $lang->id,
                    'title'       => $hub['title'],
                    'slug'        => Str::slug($hub['title']) . '-' . $lang->code,
                    'address'     => $hub['addr'],
                    'description' => $hub['desc'],
                    'category_id' => $hCatId,
                ]);
            }

            // Counter/Spek untuk setiap lokasi
            $specs = [
                ['label_id' => 'Pembatalan Gratis', 'label_en' => 'Free Cancellation', 'value' => '100%'],
                ['label_id' => 'Trip Selesai', 'label_en' => 'Trips Done', 'value' => '2500+'],
                ['label_id' => 'Armada Kapal', 'label_en' => 'Boat Fleets', 'value' => '15+'],
                ['label_id' => 'Dukungan', 'label_en' => 'Support', 'value' => '24/7'],
            ];
            foreach ($specs as $key => $s) {
                $counter = HotelCounter::create(['hotel_id' => $hotel->id, 'key' => $key]);
                HotelCounterContent::create([
                    'hotel_counter_id' => $counter->id, 'language_id' => $idLang,
                    'label' => $s['label_id'], 'value' => $s['value'],
                ]);
                if ($enLang) {
                    HotelCounterContent::create([
                        'hotel_counter_id' => $counter->id, 'language_id' => $enLang,
                        'label' => $s['label_en'], 'value' => $s['value'],
                    ]);
                }
            }
        }

        // =====================================================
        // 2. Buat 15 Perahu
        // =====================================================
        $boats = [
            // Marina Ancol Hub (5 perahu)
            ['hub' => 0, 'name' => 'Samudra Hunter 01',   'cap' => 'Capt. Bambang Sutrisno', 'eng1' => 'Yamaha F250HP',    'eng2' => 'Yamaha F250HP',   'crew' => 4, 'price' => 5500000,  'adult' => 10, 'len' => 18, 'wid' => 5],
            ['hub' => 0, 'name' => 'Azure Blue Sport',    'cap' => 'Capt. Surya Pratama',    'eng1' => 'Suzuki DF200HP',   'eng2' => 'Suzuki DF200HP',  'crew' => 3, 'price' => 4800000,  'adult' => 8,  'len' => 16, 'wid' => 4],
            ['hub' => 0, 'name' => 'Ocean Pro Jakarta',   'cap' => 'Capt. Rizal Maulana',    'eng1' => 'Honda BF225HP',    'eng2' => 'Honda BF225HP',   'crew' => 3, 'price' => 5200000,  'adult' => 10, 'len' => 17, 'wid' => 4],
            ['hub' => 0, 'name' => 'Striker GT 2024',     'cap' => 'Capt. Wahyu Santoso',    'eng1' => 'Yamaha F250HP',    'eng2' => 'Yamaha F250HP',   'crew' => 3, 'price' => 6000000,  'adult' => 10, 'len' => 19, 'wid' => 5],
            ['hub' => 0, 'name' => 'Blue Marlin Sport',   'cap' => 'Capt. Doni Setiawan',    'eng1' => 'Suzuki DF200HP',   'eng2' => null,              'crew' => 2, 'price' => 3500000,  'adult' => 8,  'len' => 14, 'wid' => 4],
            // Pantai Mutiara Hub (5 perahu)
            ['hub' => 1, 'name' => 'Mutiara Luxury 88',  'cap' => 'Capt. Andre Wijaya',     'eng1' => 'Yanmar Diesel 400HP','eng2' => 'Yanmar Diesel 400HP','crew' => 5, 'price' => 15000000, 'adult' => 15, 'len' => 26, 'wid' => 7],
            ['hub' => 1, 'name' => 'Crystal Sea Yacht',  'cap' => 'Capt. Hendra Kurniawan', 'eng1' => 'Volvo Penta 350HP', 'eng2' => 'Volvo Penta 350HP', 'crew' => 4, 'price' => 12500000, 'adult' => 12, 'len' => 24, 'wid' => 6],
            ['hub' => 1, 'name' => 'Diamond Wave 05',    'cap' => 'Capt. Rudi Hartono',     'eng1' => 'Mercury Verado 300', 'eng2' => 'Mercury Verado 300','crew' => 3, 'price' => 11000000, 'adult' => 10, 'len' => 22, 'wid' => 6],
            ['hub' => 1, 'name' => 'Sea Hawk Premium',   'cap' => 'Capt. Tony Halim',       'eng1' => 'Honda BF250HP',     'eng2' => 'Honda BF250HP',     'crew' => 3, 'price' => 8000000,  'adult' => 10, 'len' => 20, 'wid' => 5],
            ['hub' => 1, 'name' => 'Yacht Paradise JKT', 'cap' => 'Capt. Marco Santoso',    'eng1' => 'MTU V12 Diesel',    'eng2' => 'MTU V12 Diesel',    'crew' => 6, 'price' => 25000000, 'adult' => 20, 'len' => 35, 'wid' => 8],
            // Muara Angke Hub (5 perahu)
            ['hub' => 2, 'name' => 'Angler King Express', 'cap' => 'Capt. Yusuf Rahman',   'eng1' => 'Yamaha F200HP',    'eng2' => 'Yamaha F200HP',   'crew' => 3, 'price' => 4500000,  'adult' => 12, 'len' => 16, 'wid' => 4],
            ['hub' => 2, 'name' => 'Barakuda Trip',       'cap' => 'Capt. Deni Pratama',    'eng1' => 'Yamaha 85HP x2',   'eng2' => null,              'crew' => 2, 'price' => 2800000,  'adult' => 10, 'len' => 13, 'wid' => 3],
            ['hub' => 2, 'name' => 'Cakalang Explorer',   'cap' => 'Capt. Arif Budiman',    'eng1' => 'Dongfang 45HP',    'eng2' => null,              'crew' => 2, 'price' => 2200000,  'adult' => 8,  'len' => 12, 'wid' => 3],
            ['hub' => 2, 'name' => 'GT Predator 01',      'cap' => 'Capt. Guntur Wibowo',   'eng1' => 'Suzuki DF250HP',   'eng2' => 'Suzuki DF250HP',  'crew' => 4, 'price' => 7000000,  'adult' => 12, 'len' => 20, 'wid' => 5],
            ['hub' => 2, 'name' => 'Traditional Line',    'cap' => 'Capt. Salim Abdullah',  'eng1' => 'Inboard 30HP',     'eng2' => null,              'crew' => 2, 'price' => 1800000,  'adult' => 10, 'len' => 11, 'wid' => 3],
        ];

        $addServices = AdditionalService::pluck('id')->toArray();
        $roomIds = [];

        foreach ($boats as $b) {
            $hub = $hubs[$b['hub']];
            $isLuxury = $b['price'] > 10000000;

            $perahu = Perahu::create([
                'hotel_id'          => $hotelIds[$b['hub']],
                'vendor_id'         => $vendor->id,
                'nama_km'           => $b['name'],
                'captain_name'      => $b['cap'],
                'latitude'          => $hub['lat'],
                'longitude'         => $hub['lng'],
                'status'            => 1,
                'adult'             => $b['adult'],
                'price_day_1'       => $b['price'],
                'price_day_2'       => round($b['price'] * 1.9),
                'price_day_3'       => round($b['price'] * 2.7),
                'average_rating'    => 5,
                'boat_length'       => $b['len'],
                'boat_width'        => $b['wid'],
                'crew_count'        => $b['crew'],
                'engine_1'          => $b['eng1'],
                'engine_2'          => $b['eng2'],
                'bait'              => 1,
                'fishing_gear'      => 1,
                'life_jacket'       => 1,
                'breakfast'         => $isLuxury ? 1 : rand(0, 1),
                'lunch'             => 1,
                'dinner'            => $isLuxury ? 1 : 0,
                'mineral_water'     => 1,
                'ac'                => $isLuxury ? 1 : rand(0, 1),
                'wifi'              => $isLuxury ? 1 : 0,
                'electricity'       => 1,
                'stove'             => 1,
                'refrigerator'      => 1,
                'number_of_rooms_of_this_same_type' => 1,
                'booking_type'      => 'direct',
                'deposit_type'      => 'full',
                'deposit_amount'    => round($b['price'] * 0.3),
            ]);

            $roomIds[] = $perahu->id;

            // Konten untuk setiap bahasa
            $descId = "Nikmati pengalaman memancing tak terlupakan bersama {$b['name']}. Dipimpin oleh {$b['cap']} yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas {$b['adult']} orang, bermesin {$b['eng1']}" . ($b['eng2'] ? " & {$b['eng2']}" : "") . ". Kru terlatih sebanyak {$b['crew']} orang siap melayani Anda sepanjang perjalanan.";
            $descEn  = "Enjoy an unforgettable fishing experience aboard {$b['name']}. Led by {$b['cap']} with extensive experience in Jakarta Bay and Thousand Islands. Capacity {$b['adult']} guests, powered by {$b['eng1']}" . ($b['eng2'] ? " & {$b['eng2']}" : "") . ". {$b['crew']} trained crew members ready to serve you.";

            foreach ($languages as $lang) {
                RoomContent::create([
                    'room_id'       => $perahu->id,
                    'language_id'   => $lang->id,
                    'room_category' => $rCatId,
                    'title'         => $b['name'],
                    'slug'          => Str::slug($b['name']) . '-' . $lang->code,
                    'address'       => $hub['addr'],
                    'description'   => ($lang->code === 'id') ? $descId : $descEn,
                ]);
            }

            // Tambahkan layanan tambahan acak
            if (!empty($addServices)) {
                $picked = (array) array_rand(array_flip($addServices), min(rand(1, 3), count($addServices)));
                $svcData = [];
                foreach ($picked as $sId) {
                    $svcData[$sId] = rand(1, 5) * 100000;
                }
                $perahu->update(['additional_service' => json_encode($svcData)]);
            }
        }

        // =====================================================
        // 3. Buat 5 User Dummy untuk Review & Booking
        // =====================================================
        $userNames = ['andi_jakarta', 'budiono_trip', 'citra_angler', 'dimas_mancing', 'eko_bahari'];
        $users = [];
        foreach ($userNames as $un) {
            $user = User::where('username', $un)->first();
            if (!$user) {
                $user = User::create([
                    'username' => $un,
                    'email'    => $un . '@gofishi.id',
                    'password' => Hash::make('password123'),
                ]);
            }
            $users[] = $user;
        }

        // =====================================================
        // 4. Review untuk setiap perahu
        // =====================================================
        $reviews = [
            "Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!",
            "Spot mancingnya jitu banget, ketemu GT dan Barakuda besar. Puas!",
            "Pelayanannya profesional. Harga sesuai fasilitas. Recommended!",
            "Berangkat dan pulang tepat waktu. Kapten berpengalaman banget.",
            "Fasilitas lengkap, ada freezer untuk ikan. Ikan tetap segar sampai rumah.",
            "Pemandangan lautnya indah, kapalnya nyaman dan bersih.",
            "Sudah sewa berkali-kali, selalu memuaskan. Kapten tahu spot rahasia!",
        ];

        foreach ($roomIds as $rid) {
            $numReviews = rand(1, 3);
            for ($i = 0; $i < $numReviews; $i++) {
                RoomReview::create([
                    'user_id'    => $users[array_rand($users)]->id,
                    'room_id'    => $rid,
                    'rating'     => rand(4, 5),
                    'review'     => $reviews[array_rand($reviews)],
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // =====================================================
        // 5. Booking Dummy untuk Dashboard Vendor
        // =====================================================
        $statuses = ['pending', 'completed'];
        foreach ($roomIds as $rid) {
            $perahu = Perahu::find($rid);
            if (!$perahu) continue;

            $u = $users[array_rand($users)];
            $checkIn = Carbon::now()->addDays(rand(2, 15));

            Booking::create([
                'order_number'    => 'GF' . Carbon::now()->format('ymdHis') . strtoupper(Str::random(4)),
                'user_id'         => $u->id,
                'vendor_id'       => $vendor->id,
                'room_id'         => $rid,
                'hotel_id'        => $perahu->hotel_id,
                'booking_name'    => ucwords(str_replace('_', ' ', $u->username)),
                'booking_email'   => $u->email,
                'booking_phone'   => '0812' . rand(10000000, 99999999),
                'check_in_date'   => $checkIn->format('Y-m-d'),
                'check_out_date'  => $checkIn->addDay()->format('Y-m-d'),
                'grand_total'     => $perahu->price_day_1,
                'total'           => $perahu->price_day_1,
                'roomPrice'       => $perahu->price_day_1,
                'payment_status'  => rand(0, 1),
                'order_status'    => $statuses[array_rand($statuses)],
                'payment_method'  => 'Bank Transfer',
                'gateway_type'    => 'offline',
                'currency_text'   => 'IDR',
                'currency_symbol' => 'Rp',
                'age_confirmed'   => 1,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('');
        $this->command->info('✅ CleanProductSeeder selesai!');
        $this->command->info('   - ' . count($hubs) . ' Lokasi/Dermaga dibuat');
        $this->command->info('   - ' . count($boats) . ' Perahu dibuat');
        $this->command->info('   - ' . count($users) . ' User dummy dibuat');
    }
}
