<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendor;
use App\Models\Location\City;
use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\BoatPackage;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // 1. Create Dummy Vendor
            $vendor = Vendor::firstOrCreate(
                ['email' => 'demo@gofishi.com'],
                [
                    'username' => 'demovendor',
                    'password' => Hash::make('password'),
                    'status' => 1,
                    'phone' => '+628569877491'
                ]
            );

            // 2. Create Cities
            $cities = [
                ['name' => 'Jakarta Utara', 'language_id' => 20],
                ['name' => 'Surabaya', 'language_id' => 20],
                ['name' => 'Denpasar', 'language_id' => 20]
            ];

            foreach ($cities as $cityData) {
                City::firstOrCreate(
                    ['name' => $cityData['name'], 'language_id' => $cityData['language_id']],
                    $cityData
                );
            }

            // 3. Create Piers/Harbors (Lokasi)
            $firstPier = null;
            $piers = [
                [
                    'title' => 'Dermaga Muara Angke',
                    'address' => 'Jl. Dermaga Muara Angke, Pluit, Penjaringan, Jakarta Utara',
                    'latitude' => '-6.1065',
                    'longitude' => '106.7863',
                ],
                [
                    'title' => 'Pelabuhan Sunda Kelapa',
                    'address' => 'Jl. Maritim No. 8, Ancol, Pademangan, Jakarta Utara',
                    'latitude' => '-6.1205',
                    'longitude' => '106.8120',
                ],
                [
                    'title' => 'Dermaga Marina Ancol',
                    'address' => 'Jl. Lodan Timur No.7, Ancol, Pademangan, Jakarta Utara',
                    'latitude' => '-6.1223',
                    'longitude' => '106.8424',
                ]
            ];
            
            $dermagaCategoryId = DB::table('hotel_categories')->where('name', 'like', '%Lokasi%')->first()->id ?? 1;

            foreach ($piers as $pierData) {
                $hotel = Hotel::firstOrCreate(
                    ['vendor_id' => $vendor->id, 'latitude' => $pierData['latitude'], 'longitude' => $pierData['longitude']],
                    [
                        'status' => 1,
                        'stars' => 3,
                    ]
                );

                if (!$firstPier) {
                    $firstPier = $hotel;
                }

                HotelContent::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'language_id' => 20],
                    [
                        'category_id' => $dermagaCategoryId,
                        'title' => $pierData['title'],
                        'slug' => Str::slug($pierData['title']),
                        'address' => $pierData['address'],
                        'description' => 'Ini adalah deskripsi dummy untuk ' . $pierData['title'],
                    ]
                );
            }
            
            // 4. Create a Dummy Boat (Perahu) for the vendor
            if ($firstPier) {
                $boat = Perahu::firstOrCreate(
                    ['vendor_id' => $vendor->id, 'hotel_id' => $firstPier->id],
                    [
                        'status' => 1,
                        'adult' => 10,
                    ]
                );

                $boatCategory = DB::table('room_categories')->where('name', 'like', '%Mancing%')->first()->id ?? 1;

                RoomContent::firstOrCreate(
                    ['room_id' => $boat->id, 'language_id' => 20],
                    [
                        'title' => 'Perahu Mancing KM Demo Ikan',
                        'slug' => 'perahu-mancing-km-demo-ikan',
                        'room_category' => $boatCategory,
                        'description' => 'Perahu demo untuk memancing di sekitar Muara Angke. Dilengkapi dengan fasilitas standar.'
                    ]
                );

                // 5. Create Dummy Packages for the Boat
                BoatPackage::firstOrCreate(
                    ['room_id' => $boat->id, 'name' => 'Paket Harian Pulau Seribu'],
                    [
                        'price' => 1500000,
                        'duration_days' => 1,
                        'meeting_time' => '07:00:00',
                        'return_time' => '17:00:00',
                        'area' => 'Kepulauan Seribu',
                        'status' => 1
                    ]
                );

                BoatPackage::firstOrCreate(
                    ['room_id' => $boat->id, 'name' => 'Paket Malam Hari'],
                    [
                        'price' => 2500000,
                        'duration_days' => 1,
                        'meeting_time' => '19:00:00',
                        'return_time' => '05:00:00',
                        'area' => 'Karang Dalam',
                        'status' => 1
                    ]
                );
            }

            $this->command->info('Demo vendor, cities, piers, boat, and packages seeded successfully!');
        });
    }
}
