<?php

namespace App\Actions\Room;

use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomImage;
use App\Models\HourlyRoomPrice;
use App\Models\BookingHour;
use App\Models\Hotel;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Purifier;

final class CreateRoomAction
{
    public function __invoke(array $data): Perahu
    {
        // 1. Handle Feature Image
        if (isset($data['feature_image']) && $data['feature_image'] instanceof \Illuminate\Http\UploadedFile) {
            $img = $data['feature_image'];
            $ext = $img->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            $directory = public_path('assets/img/perahu/featureImage/');
            
            if (!file_exists($directory)) {
                @mkdir($directory, 0777, true);
            }
            $img->move($directory, $filename);
            $data['feature_image'] = $filename;
        }

        $data['vendor_id'] = Auth::guard('vendor')->id();
        $prices = $data['prices'] ?? [];
        $data['prices'] = json_encode($prices);

        // 2. Create the Boat
        $room = Perahu::create($data);

        // 3. Handle Hourly Prices
        if (!empty(array_filter($prices))) {
            $hours = BookingHour::orderBy('serial_number', 'asc')->get();
            foreach ($hours as $index => $hour) {
                if (isset($prices[$index])) {
                    HourlyRoomPrice::create([
                        'room_id' => $room->id,
                        'vendor_id' => $room->vendor_id,
                        'hotel_id' => $room->hotel_id,
                        'hour_id' => $hour->id,
                        'hour' => $hour->hour,
                        'price' => $prices[$index],
                    ]);
                }
            }
        }

        // 4. Update Min/Max Prices for Room and Hotel
        $room->update([
            'min_price' => roomMinPrice($room->id),
            'max_price' => roomMaxPrice($room->id),
        ]);

        $hotel = Hotel::find($room->hotel_id);
        if ($hotel) {
            $hotel->update([
                'min_price' => hotelMinPrice($hotel->id),
                'max_price' => hotelMaxPrice($hotel->id),
            ]);
        }

        // 5. Handle Slider Images
        if (isset($data['slider_images'])) {
            $sliders = array_slice($data['slider_images'], 0, 10);
            RoomImage::whereIn('id', $sliders)->update(['room_id' => $room->id]);
        }

        // 6. Handle Multilingual Contents
        $languages = Language::all();
        foreach ($languages as $language) {
            $code = $language->code;
            if ($language->is_default == 1 || !empty($data[$code . '_title'])) {
                RoomContent::create([
                    'language_id' => $language->id,
                    'room_id' => $room->id,
                    'title' => $data[$code . '_title'] ?? null,
                    'slug' => createSlug($data[$code . '_title'] ?? ''),
                    'room_category' => $data[$code . '_room_category'] ?? null,
                    'amenities' => json_encode($data[$code . '_amenities'] ?? []),
                    'description' => Purifier::clean($data[$code . '_description'] ?? '', 'youtube'),
                    'meta_keyword' => $data[$code . '_meta_keyword'] ?? null,
                    'meta_description' => $data[$code . '_meta_description'] ?? null,
                ]);
            }
        }

        return $room;
    }
}
