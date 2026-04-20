<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenitie;
use App\Models\AdditionalService;
use App\Models\AdditionalServiceContent;
use App\Models\HotelCounter;
use App\Models\HotelCounterContent;
use App\Models\Language;

class GlobalMetadataSeeder extends Seeder
{
    public function run()
    {
        $idLang = Language::where('code', 'id')->first()->id ?? 1;
        $enLang = Language::where('code', 'en')->first()->id ?? 2;

        // 1. Amenities (Fasilitas Global)
        $amenities = [
            ['title_id' => 'Wifi', 'title_en' => 'Wifi', 'icon' => 'fa fa-wifi'],
            ['title_id' => 'AC', 'title_en' => 'AC', 'icon' => 'fa fa-snowflake'],
            ['title_id' => 'Sarapan', 'title_en' => 'Breakfast', 'icon' => 'fa fa-coffee'],
            ['title_id' => 'Makan Siang', 'title_en' => 'Lunch', 'icon' => 'fa fa-utensils'],
            ['title_id' => 'Makan Malam', 'title_en' => 'Dinner', 'icon' => 'fa fa-drumstick-bite'],
            ['title_id' => 'Air Mineral', 'title_en' => 'Mineral Water', 'icon' => 'fa fa-tint'],
            ['title_id' => 'Peralatan Mancing', 'title_en' => 'Fishing Gear', 'icon' => 'fa fa-ship'],
            ['title_id' => 'Pelampung', 'title_en' => 'Life Jacket', 'icon' => 'fa fa-life-ring'],
        ];

        foreach ($amenities as $item) {
            Amenitie::create([
                'language_id' => $idLang,
                'title' => $item['title_id'],
                'icon' => $item['icon']
            ]);

            if ($enLang) {
                Amenitie::create([
                    'language_id' => $enLang,
                    'title' => $item['title_en'],
                    'icon' => $item['icon']
                ]);
            }
        }

        // 2. Additional Services (Layanan Tambahan Global)
        $services = [
            ['title_id' => 'Makan di Kapal', 'title_en' => 'In-Boat Dining'],
            ['title_id' => 'Dekorasi Kapal', 'title_en' => 'Boat Decoration'],
            ['title_id' => 'Antar Jemput Bandara', 'title_en' => 'Airport Pickup'],
            ['title_id' => 'Penyewaan Alat Selam', 'title_en' => 'Diving Gear Rental'],
        ];

        foreach ($services as $item) {
            $service = AdditionalService::create(['status' => 1, 'serial_number' => 1]);
            
            AdditionalServiceContent::create([
                'additional_service_id' => $service->id,
                'language_id' => $idLang,
                'title' => $item['title_id']
            ]);

            if ($enLang) {
                AdditionalServiceContent::create([
                    'additional_service_id' => $service->id,
                    'language_id' => $enLang,
                    'title' => $item['title_en']
                ]);
            }
        }

        // 3. Default Specs (Specs/Counters untuk Lokasi)
        $hotels = \App\Models\Hotel::all();
        foreach ($hotels as $hotel) {
            $specs = [
                ['label_id' => 'Pembatalan Gratis', 'label_en' => 'Free Cancellation', 'value' => '100%'],
                ['label_id' => 'Tamu Terdaftar', 'label_en' => 'Registered Guests', 'value' => '2500+'],
                ['label_id' => 'Armada Kapal', 'label_en' => 'Boat Fleets', 'value' => '100+'],
                ['label_id' => 'Dukungan 24/7', 'label_en' => '24/7 Support', 'value' => 'OK'],
            ];

            foreach ($specs as $key => $s) {
                $counter = HotelCounter::create(['hotel_id' => $hotel->id, 'key' => $key]);
                
                HotelCounterContent::create([
                    'hotel_counter_id' => $counter->id,
                    'language_id' => $idLang,
                    'label' => $s['label_id'],
                    'value' => $s['value']
                ]);

                if ($enLang) {
                    HotelCounterContent::create([
                        'hotel_counter_id' => $counter->id,
                        'language_id' => $enLang,
                        'label' => $s['label_en'],
                        'value' => $s['value']
                    ]);
                }
            }
        }
    }
}
