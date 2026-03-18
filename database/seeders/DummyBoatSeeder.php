<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomCategory;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyBoatSeeder extends Seeder
{
    public function run()
    {
        $vendors = Vendor::take(2)->get();
        if ($vendors->count() === 0) return;

        $hotels = Hotel::take(5)->get();
        if ($hotels->count() === 0) return;

        $boatsData = [
            ['title' => 'Luxury Yacht Serenade', 'nama_km' => 'KM Serenade', 'type' => 'Yacht', 'price' => 25000000, 'adult' => 15, 'crew' => 5],
            ['title' => 'Speedboat Manta Ray', 'nama_km' => 'KM Manta', 'type' => 'Speedboat', 'price' => 3500000, 'adult' => 6, 'crew' => 2],
            ['title' => 'Phinisi Ocean Explorer', 'nama_km' => 'KM Explorer', 'type' => 'Phinisi', 'price' => 18000000, 'adult' => 20, 'crew' => 8],
            ['title' => 'Fishing Boat Barracuda', 'nama_km' => 'KM Barracuda', 'type' => 'Fishing', 'price' => 2500000, 'adult' => 8, 'crew' => 3],
            ['title' => 'Catamaran Blue Sea', 'nama_km' => 'KM Blue Sea', 'type' => 'Catamaran', 'price' => 12000000, 'adult' => 12, 'crew' => 4],
            ['title' => 'Party Boat Nusantara', 'nama_km' => 'KM Nusantara', 'type' => 'Party Boat', 'price' => 15000000, 'adult' => 25, 'crew' => 6],
            ['title' => 'Standard Fishing Boat', 'nama_km' => 'KM Nelayan 1', 'type' => 'Fishing', 'price' => 1200000, 'adult' => 5, 'crew' => 2],
            ['title' => 'Speedboat Arrow', 'nama_km' => 'KM Arrow', 'type' => 'Speedboat', 'price' => 4000000, 'adult' => 8, 'crew' => 2],
            ['title' => 'Private Yacht Diamond', 'nama_km' => 'KM Diamond', 'type' => 'Yacht', 'price' => 30000000, 'adult' => 10, 'crew' => 4],
            ['title' => 'Wooden Boat Classic', 'nama_km' => 'KM Classic', 'type' => 'Wooden', 'price' => 2000000, 'adult' => 10, 'crew' => 3],
            ['title' => 'Diving Boat Poseidon', 'nama_km' => 'KM Poseidon', 'type' => 'Diving', 'price' => 5000000, 'adult' => 12, 'crew' => 4],
            ['title' => 'Speedboat Flash', 'nama_km' => 'KM Flash', 'type' => 'Speedboat', 'price' => 3000000, 'adult' => 5, 'crew' => 2],
            ['title' => 'Luxury Phinisi Raja', 'nama_km' => 'KM Raja', 'type' => 'Phinisi', 'price' => 22000000, 'adult' => 18, 'crew' => 7],
            ['title' => 'Fishing Boat Tuna', 'nama_km' => 'KM Tuna', 'type' => 'Fishing', 'price' => 2800000, 'adult' => 7, 'crew' => 3],
            ['title' => 'Katamaran Sunset', 'nama_km' => 'KM Sunset', 'type' => 'Catamaran', 'price' => 14000000, 'adult' => 14, 'crew' => 4],
        ];

        foreach ($boatsData as $index => $data) {
            $hotel = $hotels[$index % $hotels->count()];
            $vendor = $vendors[$index % $vendors->count()];
            
            // Create Category if not exist
            $category = RoomCategory::firstOrCreate([
                'name' => $data['type'],
                'language_id' => 1, // Store default EN/ID category
                'status' => 1,
            ], [
                'serial_number' => rand(1, 100)
            ]);
            
            // Generate a random image ID from picsum or use specific seeds
            $imageId = 100 + $index;
            $feature_image = "https://picsum.photos/seed/{$imageId}/800/600";
            
            // Check if already exist
            $contentExists = RoomContent::where('title', $data['title'])->first();
            if ($contentExists) continue;

            $room = Perahu::create([
                'hotel_id' => $hotel->id,
                'vendor_id' => $vendor->id,
                'status' => 1,
                'feature_image' => $feature_image,
                'nama_km' => $data['nama_km'],
                'average_rating' => rand(40, 50) / 10,
                'price_day_1' => $data['price'],
                'min_price' => $data['price'],
                'max_price' => $data['price'],
                'adult' => $data['adult'],
                'children' => rand(0, 3),
                'bedroom_count' => rand(1, 4),
                'toilet_count' => rand(1, 3),
                'boat_length' => rand(10, 30) . 'm',
                'boat_width' => rand(3, 8) . 'm',
                'crew_count' => $data['crew'],
                'engine_1' => 'Yamaha ' . rand(100, 300) . 'HP',
                'latitude' => $hotel->latitude,
                'longitude' => $hotel->longitude,
            ]);

            foreach ([1, 2] as $langId) {
                // Determine title based on language logic if needed
                $langTitle = $hotel->hotel_contents()->where('language_id', $langId)->first();
                $hotelName = $langTitle ? $langTitle->title : 'Destinasi';
                
                RoomContent::create([
                    'room_id' => $room->id,
                    'language_id' => $langId,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']),
                    'address' => 'Area ' . $hotelName,
                    'description' => 'Disewakan perahu / kapal ' . $data['title'] . ' siap melayani wisata atau memancing di ' . $hotelName . ' dengan fasilitas terbaik.',
                    'room_category' => $category->id,
                ]);
            }
        }
    }
}
