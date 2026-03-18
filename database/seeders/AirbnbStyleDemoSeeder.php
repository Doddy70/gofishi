<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\Language;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AirbnbStyleDemoSeeder extends Seeder
{
    public function run()
    {
        $lang = Language::where('is_default', 1)->first() ?? Language::first();
        $vendor = Vendor::first() ?? Vendor::create([
            'username' => 'host_pro',
            'email' => 'host@example.com',
            'password' => bcrypt('password'),
            'status' => 1
        ]);

        $boats = [
            [
                'name' => 'Blue Wave Sport Cruiser',
                'location' => 'Ancol, Jakarta',
                'price' => 4500000,
                'image' => 'https://images.unsplash.com/photo-1567891740298-2b97c34c45b5?q=80&w=800',
                'guests' => 8,
                'engine' => 'Suzuki 250HP'
            ],
            [
                'name' => 'Luxury Ocean Explorer',
                'location' => 'Pantai Mutiara, Jakarta',
                'price' => 15000000,
                'image' => 'https://images.unsplash.com/photo-1569263979104-865ab7cd8d13?q=80&w=800',
                'guests' => 12,
                'engine' => 'Twin Yamaha 300HP'
            ],
            [
                'name' => 'Traditional Angler Boat',
                'location' => 'Muara Angke, Jakarta',
                'price' => 1800000,
                'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=800',
                'guests' => 6,
                'engine' => 'Diesel Yanmar'
            ],
            [
                'name' => 'Island Hopper Speedboat',
                'location' => 'Tanjung Pasir, Banten',
                'price' => 3200000,
                'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=800',
                'guests' => 10,
                'engine' => 'Yamaha 200HP'
            ],
            [
                'name' => 'Sunset Yacht Club',
                'location' => 'Marina Batavia, Jakarta',
                'price' => 25000000,
                'image' => 'https://images.unsplash.com/photo-1605281317010-fe5079366620?q=80&w=800',
                'guests' => 15,
                'engine' => 'MTU Diesel'
            ]
        ];

        foreach ($boats as $data) {
            // Create a dummy hotel for location
            $hotel = Hotel::create([
                'vendor_id' => $vendor->id,
                'status' => 1,
                'latitude' => -6.12,
                'longitude' => 106.83
            ]);

            HotelContent::create([
                'hotel_id' => $hotel->id,
                'language_id' => $lang->id,
                'title' => $data['location'],
                'slug' => Str::slug($data['location'] . '-' . uniqid()),
                'address' => $data['location']
            ]);

            $perahu = Perahu::create([
                'nama_km' => $data['name'],
                'hotel_id' => $hotel->id,
                'vendor_id' => $vendor->id,
                'status' => 1,
                'adult' => $data['guests'],
                'price_day_1' => $data['price'],
                'average_rating' => rand(4, 5),
                'engine_1' => $data['engine'],
                'feature_image' => $data['image'],
                'bedroom_count' => rand(1, 3),
                'toilet_count' => rand(1, 2),
                'crew_count' => rand(2, 5)
            ]);

            RoomContent::create([
                'room_id' => $perahu->id,
                'language_id' => $lang->id,
                'title' => $data['name'],
                'slug' => Str::slug($data['name'] . '-' . uniqid()),
                'address' => $data['location'],
                'description' => 'Nikmati pengalaman memancing dan berlayar terbaik dengan perahu ' . $data['name'] . '. Dilengkapi fasilitas standar keamanan tinggi.'
            ]);
        }
    }
}
