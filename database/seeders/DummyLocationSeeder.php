<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyLocationSeeder extends Seeder
{
    public function run()
    {
        $vendor = Vendor::first(); // Grab first vendor

        $locations = [
            ['title' => 'Marina Ancol', 'city' => 'Jakarta Utara', 'lat' => -6.1214, 'lng' => 106.8335],
            ['title' => 'Pantai Mutiara', 'city' => 'Penjaringan', 'lat' => -6.1083, 'lng' => 106.7972],
            ['title' => 'Muara Angke', 'city' => 'Jakarta Utara', 'lat' => -6.1065, 'lng' => 106.7758],
            ['title' => 'Kepulauan Seribu', 'city' => 'DKI Jakarta', 'lat' => -5.5891, 'lng' => 106.5615],
            ['title' => 'Tanjung Pasir', 'city' => 'Tangerang', 'lat' => -6.0125, 'lng' => 106.6661],
        ];

        foreach ($locations as $loc) {
            $slug = Str::slug($loc['title']);
            
            // Periksa jika hotel_content dengan title ini sudah ada
            $contentExists = HotelContent::where('title', $loc['title'])->first();
            if ($contentExists) {
                continue;
            }

            $hotel = Hotel::create([
                'vendor_id' => $vendor->id,
                'status' => 1,
                'stars' => rand(3, 5),
                'average_rating' => rand(40, 50) / 10,
                'latitude' => $loc['lat'],
                'longitude' => $loc['lng'],
            ]);

            // Untuk Bahasa Indonesia & Inggris bisa di loop, tapi minimal 1
            foreach ([1, 2] as $langId) {
                HotelContent::create([
                    'hotel_id' => $hotel->id,
                    'language_id' => $langId,
                    'title' => $loc['title'],
                    'slug' => $slug,
                    'address' => $loc['city'],
                    'description' => 'Pelabuhan ' . $loc['title'] . ' adalah titik kumpul perahu/yacht charter premium di area ' . $loc['city'] . ' terbaik.',
                ]);
            }
        }
    }
}
