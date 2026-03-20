<?php

namespace App\Services;

use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomReview;
use App\Models\Holiday;
use App\Models\Booking;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\BasicSettings\Basic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class PerahuService
{
    /**
     * Get cached basic settings to avoid redundant DB calls.
     */
    public function getSettings()
    {
        return Cache::remember('basic_settings', 3600, function () {
            return Basic::first();
        });
    }

    /**
     * Efficiently get available rooms based on filters.
     * Replaces manual loops with optimized SQL queries.
     */
    public function getAvailableRooms(array $filters, int $languageId, int $perPage = 12)
    {
        $startDate = !empty($filters['checkInDates']) ? Carbon::parse($filters['checkInDates']) : null;

        $query = Perahu::query()
            ->with([
                'room_content' => function($q) use ($languageId) { $q->where('language_id', $languageId); },
                'hotel.hotel_contents' => function($q) use ($languageId) { $q->where('language_id', $languageId); },
                'vendor'
            ])
            ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->where('room_contents.language_id', $languageId)
            ->where('hotel_contents.language_id', $languageId)
            ->where('rooms.status', 1)
            ->where('hotels.status', 1);

        // Geolocation Logic (Support for direct coords or location string)
        $lat = $filters['lat'] ?? null;
        $lng = $filters['lng'] ?? null;

        if (!empty($filters['location']) && empty($lat)) {
            $settings = $this->getSettings();
            if ($settings && $settings->google_map_api_key_status == 1 && !empty(config('google.maps_api_key'))) {
                $coordinates = \App\Http\Helpers\GeoSearch::getCoordinates($filters['location'], config('google.maps_api_key'));
                if ($coordinates && !isset($coordinates['error'])) {
                    $lat = $coordinates['lat'];
                    $lng = $coordinates['lng'];
                }
            }
        }

        if (!empty($lat) && !empty($lng)) {
            $query->selectRaw("rooms.*, (6371 * acos(cos(RADIANS(?)) * cos(RADIANS(hotels.latitude)) * cos(RADIANS(hotels.longitude) - RADIANS(?)) + sin(RADIANS(?)) * sin(RADIANS(hotels.latitude)))) AS distance", [$lat, $lng, $lat]);
            $query->orderBy('distance', 'asc');
        } else {
            // Text search fallback
            if (!empty($filters['location'])) {
                $location = $filters['location'];
                $query->where(function($q) use ($location) {
                    $q->where('hotel_contents.title', 'like', '%' . $location . '%')
                      ->orWhere('room_contents.address', 'like', '%' . $location . '%')
                      ->orWhere('rooms.nama_km', 'like', '%' . $location . '%');
                });
            }
            $query->orderBy('rooms.id', 'desc');
        }

        // Filter by Guest Capacity (Sum of adults + children)
        if (!empty($filters['adults'])) {
            $query->where('rooms.adult', '>=', (int)$filters['adults']);
        }

        // Filter by Keyword (from AI Recommendation or Manual Search)
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function($q) use ($keyword) {
                $q->where('room_contents.title', 'like', '%' . $keyword . '%')
                  ->orWhere('rooms.nama_km', 'like', '%' . $keyword . '%');
            });
        }
        
        // Daily Availability Logic
        $hasCheckInDates = !empty($filters['checkInDates']);
        $hasCheckInOut = !empty($filters['checkIn']) && !empty($filters['checkOut']);

        if ($hasCheckInDates || $hasCheckInOut) {
            $start = null; $end = null;
            if ($hasCheckInDates && strpos($filters['checkInDates'], ' - ') !== false) {
                $dates = explode(' - ', $filters['checkInDates']);
                $start = Carbon::parse($dates[0]);
                $end = Carbon::parse($dates[1]);
            } elseif (!empty($filters['checkIn']) && !empty($filters['checkOut'])) {
                $start = Carbon::parse($filters['checkIn']);
                $end = Carbon::parse($filters['checkOut']);
            }

            if ($start && $end) {
                // Determine whether a room is booked during this time interval
                $query->whereNotIn('rooms.id', function ($subQuery) use ($start, $end) {
                    $subQuery->select('room_id')
                             ->from('bookings')
                             ->where('payment_status', '!=', 2) // Exclude rejected/failed
                             ->where(function($q) use ($start, $end) {
                                 $q->whereBetween('check_in_date_time', [$start, $end])
                                   ->orWhereBetween('check_out_date_time', [$start, $end]);
                             });
                });
            }
        }

        // Final selection including calculated distance and hotel coordinates
        $finalSelect = ['rooms.*', 'room_contents.title', 'room_contents.slug', 'hotel_contents.title as hotelName', 'hotels.latitude', 'hotels.longitude'];
        if (!empty($lat)) {
             $finalSelect[] = \Illuminate\Support\Facades\DB::raw("(6371 * acos(cos(RADIANS($lat)) * cos(RADIANS(hotels.latitude)) * cos(RADIANS(hotels.longitude) - RADIANS($lng)) + sin(RADIANS($lat)) * sin(RADIANS(hotels.latitude)))) AS distance");
        }

        return $query->select($finalSelect)->paginate($perPage);
    }

    /**
     * Get detailed room information with translations.
     */
    public function getRoomDetails(int $id, int $languageId)
    {
        return Perahu::with([
                'room_content' => fn($q) => $q->where('language_id', $languageId),
                'hotel.hotel_contents' => fn($q) => $q->where('language_id', $languageId),
                'vendor',
                'room_galleries',
                'packages'
            ])
            ->where('rooms.status', 1)
            ->findOrFail($id);
    }

    /**
     * Calculate holiday dates for a specific hotel.
     */
    public function getHotelHolidays(int $hotelId)
    {
        return Holiday::where('hotel_id', $hotelId)
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->format('m/d/Y'))
            ->toArray();
    }

    /**
     * Get reviews with user information efficiently.
     */
    public function getRoomReviews(int $roomId)
    {
        $reviews = RoomReview::with('userInfo')
            ->where('room_id', $roomId)
            ->orderByDesc('id')
            ->get();

        return [
            'reviews' => $reviews,
            'numOfReview' => $reviews->count()
        ];
    }

    /**
     * Get related rooms in the same category.
     */
    public function getRelatedRooms(int $roomId, int $categoryId, int $languageId)
    {
        return Perahu::query()
            ->with(['room_content' => fn($q) => $q->where('language_id', $languageId)])
            ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->where('room_contents.language_id', $languageId)
            ->where('rooms.status', 1)
            ->where('hotels.status', 1)
            ->where('rooms.id', '!=', $roomId)
            ->where('room_contents.room_category', $categoryId)
            ->select('rooms.*', 'room_contents.title', 'room_contents.slug')
            ->limit(4)
            ->get();
    }
}
