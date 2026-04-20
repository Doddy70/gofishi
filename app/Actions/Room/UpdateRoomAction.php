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

final class UpdateRoomAction
{
    public function __invoke(Perahu $room, array $data): Perahu
    {
        // 1. Handle Feature Image (Thumbnail) Update
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
            $img = $data['thumbnail'];
            $ext = $img->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $directory = public_path('assets/img/perahu/featureImage/');
            
            if (!file_exists($directory)) {
                @mkdir($directory, 0777, true);
            }
            
            // Delete old image
            if ($room->feature_image && file_exists($directory . $room->feature_image)) {
                @unlink($directory . $room->feature_image);
            }
            
            $img->move($directory, $filename);
            $data['feature_image'] = $filename;
        }

        $prices = $data['prices'] ?? [];
        if (is_array($prices) && !empty(array_filter($prices))) {
            $data['prices'] = json_encode($prices);

            // Sync Hourly Prices
            $hours = BookingHour::orderBy('serial_number', 'asc')->get();
            foreach ($hours as $index => $hour) {
                HourlyRoomPrice::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'hour_id' => $hour->id
                    ],
                    [
                        'vendor_id' => $room->vendor_id,
                        'hotel_id' => $room->hotel_id,
                        'hour' => $hour->hour,
                        'price' => $prices[$index] ?? null,
                    ]
                );
            }
        }

        // 2. Update the Boat Model
        $room->update($data);

        // 3. Update Min/Max Prices
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

        // 4. Handle Slider Images (Append logic)
        if (isset($data['slider_images'])) {
            $existing_count = RoomImage::where('room_id', $room->id)->count();
            $available_slots = max(0, 10 - $existing_count);
            
            if ($available_slots > 0) {
                $sliders = array_slice($data['slider_images'], 0, $available_slots);
                RoomImage::whereIn('id', $sliders)->update(['room_id' => $room->id]);
            }
        }

        // 5. Update Multilingual Contents
        $languages = Language::all();
        foreach ($languages as $language) {
            $code = $language->code;
            if ($language->is_default == 1 || !empty($data[$code . '_title'])) {
                RoomContent::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'language_id' => $language->id
                    ],
                    [
                        'title' => $data[$code . '_title'] ?? null,
                        'slug' => createSlug($data[$code . '_title'] ?? ''),
                        'room_category' => $data[$code . '_room_category'] ?? null,
                        'amenities' => json_encode($data[$code . '_amenities'] ?? []),
                        'description' => Purifier::clean($data[$code . '_description'] ?? '', 'youtube'),
                        'meta_keyword' => $data[$code . '_meta_keyword'] ?? null,
                        'meta_description' => $data[$code . '_meta_description'] ?? null,
                    ]
                );
            }
        }

        return $room;
    }
}
