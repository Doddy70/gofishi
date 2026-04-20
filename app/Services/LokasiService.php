<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Holiday;
use App\Models\Booking;
use App\Models\Perahu;
use App\Models\BasicSettings\Basic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LokasiService
{
    /**
     * Get cached basic settings.
     */
    public function getSettings()
    {
        return Cache::remember('basic_settings', 3600, function () {
            return Basic::first();
        });
    }

    /**
     * Efficiently search for hotels with available rooms.
     */
    public function getAvailableHotels(array $filters, int $languageId, int $perPage = 12)
    {
        $checkInDates = $filters['checkInDates'] ?? null;
        $checkInTimes = $filters['checkInTimes'] ?? '00:00:00';
        $hour = (int)($filters['hour'] ?? 0);

        $query = Hotel::query()
            ->with([
                'hotel_contents' => fn($q) => $q->where('language_id', $languageId),
                'vendor'
            ])
            ->join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->join('hotel_categories', 'hotel_contents.category_id', '=', 'hotel_categories.id')
            ->where('hotel_contents.language_id', $languageId)
            ->where('hotels.status', 1)
            ->where('hotel_categories.status', 1);

        // Standard Filters
        if (!empty($filters['title'])) {
            $query->where('hotel_contents.title', 'like', '%' . $filters['title'] . '%');
        }
        if (!empty($filters['category'])) {
            $query->where('hotel_categories.slug', $filters['category']);
        }
        if (!empty($filters['ratings'])) {
            $query->where('hotels.average_rating', '>=', $filters['ratings']);
        }
        if (!empty($filters['stars'])) {
            $query->where('hotels.stars', $filters['stars']);
        }
        if (!empty($filters['location_val'])) {
            $query->where('hotel_contents.address', 'like', '%' . $filters['location_val'] . '%');
        }

        // Proximity/Radius Search
        if (!empty($filters['lat']) && !empty($filters['lng'])) {
            $userLat = $filters['lat'];
            $userLng = $filters['lng'];
            $radius = $filters['radius'] ?? $this->getSettings()->radius ?? 50; // Default radius from settings or 50km

            // Haversine Formula: 6371 * acos(cos(radians(userLat)) * cos(radians(hotels.latitude)) * cos(radians(hotels.longitude) - radians(userLng)) + sin(radians(userLat)) * sin(radians(hotels.latitude)))
            $query->selectRaw("hotels.*, hotel_contents.*, hotels.id as id, (6371 * acos(cos(radians(?)) * cos(radians(hotels.latitude)) * cos(radians(hotels.longitude) - radians(?)) + sin(radians(?)) * sin(radians(hotels.latitude)))) AS distance", [$userLat, $userLng, $userLat])
                ->having('distance', '<=', $radius);
        } else {
            $query->select('hotels.*', 'hotel_contents.*', 'hotels.id as id');
        }

        // Availability Logic
        if ($checkInDates) {
            $checkInDateTime = Carbon::parse($checkInDates . ' ' . $checkInTimes);
            $checkOutDateTime = (clone $checkInDateTime)->addHours($hour);

            // 1. Exclude hotels on holiday
            $query->whereDoesntHave('holidays', function ($q) use ($checkInDateTime, $checkOutDateTime) {
                $q->whereDate('date', $checkInDateTime->toDateString())
                  ->orWhereDate('date', $checkOutDateTime->toDateString());
            });

            // 2. Include only hotels that have at least one room type with available armada
            $query->whereHas('room', function ($q) use ($checkInDateTime, $checkOutDateTime) {
                $q->where('rooms.status', 1)
                  ->whereRaw('rooms.number_of_rooms_of_this_same_type > (
                    SELECT COUNT(*) FROM bookings 
                    WHERE bookings.room_id = rooms.id 
                    AND bookings.payment_status != 2
                    AND (
                        (bookings.check_in_date_time BETWEEN ? AND ?)
                        OR (bookings.check_out_date_time BETWEEN ? AND ?)
                        OR (bookings.check_in_date_time <= ? AND bookings.check_out_date_time >= ?)
                    )
                  )', [
                    $checkInDateTime, $checkOutDateTime,
                    $checkInDateTime, $checkOutDateTime,
                    $checkInDateTime, $checkOutDateTime
                ]);
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'new';
        match ($sort) {
            'old' => $query->orderBy('hotels.id', 'asc'),
            'starhigh' => $query->orderBy('hotels.stars', 'desc'),
            'starlow' => $query->orderBy('hotels.stars', 'asc'),
            'reviewshigh' => $query->orderBy('hotels.average_rating', 'desc'),
            'reviewslow' => $query->orderBy('hotels.average_rating', 'asc'),
            default => $query->orderBy('hotels.id', 'desc'),
        };

        return $query->paginate($perPage);
    }
}
