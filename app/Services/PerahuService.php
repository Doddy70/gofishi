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
            ->where(function ($q) use ($languageId) {
                $q->where('room_contents.language_id', $languageId);
            })
            ->where(function ($q) use ($languageId) {
                $q->where('hotel_contents.language_id', $languageId);
            })
            ->where('rooms.status', 1)
            ->where('hotels.status', 1);

        // Filter by Location (Enhanced with Radius/Geo Search)
        if (!empty($filters['location'])) {
            $location = $filters['location'];
            $settings = $this->getSettings();
            
            // Try to get coordinates if API key is present
            $coordinates = null;
            if ($settings && $settings->google_map_api_key_status == 1 && !empty(config('google.maps_api_key'))) {
                $coordinates = \App\Http\Helpers\GeoSearch::getCoordinates($location, config('google.maps_api_key'));
            }

            if ($coordinates && !isset($coordinates['error'])) {
                $lat = $coordinates['lat'];
                $lng = $coordinates['lng'];
                
                // Add distance calculation (Haversine Formula) using MySQL RADIANS()
                $query->selectRaw("rooms.*, (6371 * acos(cos(RADIANS(?)) * cos(RADIANS(hotels.latitude)) * cos(RADIANS(hotels.longitude) - RADIANS(?)) + sin(RADIANS(?)) * sin(RADIANS(hotels.latitude)))) AS distance", [$lat, $lng, $lat]);
                
                // Filter by a reasonable radius (e.g., 50km for boat trips)
                $query->having('distance', '<', 50);
                $query->orderBy('distance', 'asc');
            } else {
                // Fallback to text search if no coordinates
                $query->where(function($q) use ($location) {
                    $q->where('hotel_contents.title', 'like', '%' . $location . '%')
                      ->orWhere('room_contents.address', 'like', '%' . $location . '%')
                      ->orWhere('rooms.nama_km', 'like', '%' . $location . '%');
                });
            }
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
                  ->orWhere('room_contents.summary', 'like', '%' . $keyword . '%')
                  ->orWhere('room_contents.description', 'like', '%' . $keyword . '%')
                  ->orWhere('rooms.nama_km', 'like', '%' . $keyword . '%');
            });
        }
        
        // Daily Availability Logic
        if ($startDate || (!empty($filters['checkIn']) && !empty($filters['checkOut']))) {
            $start = null;
            $end = null;

            if ($startDate && strpos($filters['checkInDates'], ' - ') !== false) {
                $dates = explode(' - ', $filters['checkInDates']);
                $start = Carbon::parse($dates[0]);
                $end = Carbon::parse($dates[1]);
            } elseif (!empty($filters['checkIn']) && !empty($filters['checkOut'])) {
                $start = Carbon::parse($filters['checkIn']);
                $end = Carbon::parse($filters['checkOut']);
            }

            if ($start && $end) {
                // 1. Exclude if any date in range is a holiday for the hotel (dermaga)
                $query->whereDoesntHave('hotel.holidays', function ($q) use ($start, $end) {
                    $q->whereBetween('date', [$start, $end]);
                });

                // 2. Exclude if the room is already booked within the range
                $query->whereDoesntHave('bookings', function ($q) use ($start, $end) {
                    $q->where('payment_status', '!=', 2) // 2 = 'cancelled'
                      ->where(function($sub) use ($start, $end) {
                      $sub->whereBetween('check_in_date_time', [$start, $end])
                          ->orWhereBetween('check_out_date_time', [$start, $end])
                          ->orWhere(function($inner) use ($start, $end) {
                              $inner->where('check_in_date_time', '<=', $start)
                                    ->where('check_out_date_time', '>=', $end);
                          });
                  });
            });
        }
    }

        return $query->select(
            'rooms.*',
            'room_contents.title',
            'room_contents.slug',
            'hotel_contents.title as hotelName'
        )->paginate($perPage);
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
