<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perahu as Room;
use App\Models\RoomContent;
use App\Models\BasicSettings\Basic;
use Carbon\Carbon;

class GuestController extends Controller
{
    /**
     * Search & Filter Boats API
     * Returns a JSON list of available boats (rooms).
     */
    public function index(Request $request)
    {
        $langCode = $request->header('Accept-Language', 'id'); // Default to Indonesian
        $language = \App\Models\Language::where('code', $langCode)->first() ?? \App\Models\Language::where('is_default', 1)->first();

        $query = Room::join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->join('hotel_contents', 'rooms.hotel_id', '=', 'hotel_contents.hotel_id')
            ->where('room_contents.language_id', $language->id)
            ->where('hotel_contents.language_id', $language->id)
            ->where('rooms.status', 1)
            ->where('hotels.status', 1);

        // Filter by Title/Name
        if ($request->filled('title')) {
            $query->where('room_contents.title', 'like', '%' . $request->title . '%');
        }

        // Filter by Location (Address)
        if ($request->filled('location')) {
            $query->where('hotel_contents.address', 'like', '%' . $request->location . '%');
        }

        // Filter by Guests (Adults)
        if ($request->filled('guests')) {
            $query->where('rooms.adult', '>=', $request->guests);
        }

        // Tiered Pricing Filters (Example: price from budget)
        if ($request->filled('min_price')) {
            $query->where('rooms.price_day_1', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('rooms.price_day_1', '<=', $request->max_price);
        }

        $boats = $query->select(
            'rooms.id',
            'room_contents.title',
            'room_contents.slug',
            'rooms.feature_image',
            'rooms.price_day_1 as price_1_day',
            'rooms.price_day_2 as price_2_days',
            'rooms.price_day_3 as price_3_days',
            'rooms.meet_time_day_1',
            'rooms.return_time_day_1',
            'rooms.meet_time_day_2',
            'rooms.return_time_day_2',
            'rooms.meet_time_day_3',
            'rooms.return_time_day_3',
            'rooms.captain_name',
            'rooms.engine_1',
            'rooms.engine_2',
            'rooms.adult as max_guests',
            'rooms.average_rating',
            'hotel_contents.address as location',
            'hotels.latitude',
            'hotels.longitude'
        )->paginate($request->input('per_page', 10));

        // Add full URL to feature image
        $boats->getCollection()->transform(function ($boat) {
            $boat->feature_image = asset('assets/img/rooms/' . $boat->feature_image);
            return $boat;
        });

        return response()->json([
            'success' => true,
            'data' => $boats
        ]);
    }

    /**
     * Boat Details API
     */
    public function show($id, Request $request)
    {
        $langCode = $request->header('Accept-Language', 'id');
        $language = \App\Models\Language::where('code', $langCode)->first() ?? \App\Models\Language::where('is_default', 1)->first();

        $boat = Room::with(['room_galleries'])
            ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
            ->join('hotel_contents', 'rooms.hotel_id', '=', 'hotel_contents.hotel_id')
            ->where('rooms.id', $id)
            ->where('room_contents.language_id', $language->id)
            ->where('hotel_contents.language_id', $language->id)
            ->select(
                'rooms.*',
                'room_contents.title',
                'room_contents.slug',
                'room_contents.description',
                'room_contents.amenities',
                'hotel_contents.address as location',
                'hotels.latitude',
                'hotels.longitude'
            )
            ->first();

        if (!$boat) {
            return response()->json(['success' => false, 'message' => 'Boat not found'], 404);
        }

        // Format amenities
        $boat->amenities = json_decode($boat->amenities);
        $boat->feature_image = asset('assets/img/rooms/' . $boat->feature_image);
        
        // Format gallery
        $boat->gallery = [];
        if($boat->room_galleries) {
            $boat->gallery = $boat->room_galleries->map(function($img) {
                return asset('assets/img/rooms/gallery/' . $img->image);
            });
        }
        unset($boat->room_galleries);

        return response()->json([
            'success' => true,
            'data' => $boat
        ]);
    }

    /**
     * Create a Boat Booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'duration_days' => 'required|in:1,2,3',
            'adults' => 'required|integer|min:1'
        ]);

        $room = Room::findOrFail($request->room_id);
        $user = auth()->user();

        // Check max guests
        if ($request->adults > $room->adult) {
            return response()->json(['success' => false, 'message' => 'Maximum guests exceeded'], 422);
        }

        // Calculate Pricing & Timing based on tier
        $tier = $request->duration_days;
        $price = $room->{"price_day_$tier"} ?? $room->daily_price;
        $meet_time = $room->{"meet_time_day_$tier"} ?? '08:00';
        $return_time = $room->{"return_time_day_$tier"} ?? '17:00';

        $check_in_date_time = $request->check_in_date . ' ' . $meet_time . ':00';
        $check_out_date = date('Y-m-d', strtotime($request->check_in_date . " + " . ($tier - 1) . " days"));
        $check_out_date_time = $check_out_date . ' ' . $return_time . ':00';

        // Basic Availability Check (Overlap)
        $isBooked = \App\Models\Booking::where('room_id', $room->id)
            ->where('payment_status', '!=', 2) // Not cancelled
            ->where(function ($query) use ($check_in_date_time, $check_out_date_time) {
                $query->whereBetween('check_in_date_time', [$check_in_date_time, $check_out_date_time])
                      ->orWhereBetween('check_out_date_time', [$check_in_date_time, $check_out_date_time]);
            })
            ->exists();

        if ($isBooked) {
            return response()->json(['success' => false, 'message' => 'Boat is already booked for these dates'], 422);
        }

        // Create Booking
        $booking = new \App\Models\Booking();
        $booking->order_number = time() . rand(100, 999);
        $booking->user_id = $user->id;
        $booking->vendor_id = $room->vendor_id;
        $booking->room_id = $room->id;
        $booking->hotel_id = $room->hotel_id;
        $booking->check_in_date = $request->check_in_date;
        $booking->check_in_date_time = $check_in_date_time;
        $booking->check_out_date = $check_out_date;
        $booking->check_out_date_time = $check_out_date_time;
        $booking->booking_name = $user->username;
        $booking->booking_email = $user->email;
        $booking->adult = $request->adults;
        $booking->total = $price;
        $booking->grand_total = $price;
        $booking->payment_status = 0; // Pending
        $booking->save();

        // Trigger Notification (Phase 4 Mails)
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\BookingRequestedMail($booking));
            // Also notify Host/Vendor if applicable
            if ($room->vendor && $room->vendor->email) {
                 \Illuminate\Support\Facades\Mail::to($room->vendor->email)->send(new \App\Mail\BookingRequestedMail($booking));
            }
        } catch (\Exception $e) {
            // Log mail error but don't fail booking
            \Illuminate\Support\Facades\Log::error('API Booking Mail Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking request submitted successfully',
            'data' => $booking
        ]);
    }

    /**
     * Cancel a Booking
     */
    public function cancel($id)
    {
        $user = auth()->user();
        $booking = \App\Models\Booking::where('id', $id)->where('user_id', $user->id)->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        if ($booking->payment_status == 1) {
             return response()->json(['success' => false, 'message' => 'Cannot cancel an already paid booking'], 422);
        }

        $booking->payment_status = 2; // Cancelled
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully'
        ]);
    }

    /**
     * Get Booking History for Current User
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $langCode = $request->header('Accept-Language', 'id');
        $language = \App\Models\Language::where('code', $langCode)->first() ?? \App\Models\Language::where('is_default', 1)->first();

        $bookings = \App\Models\Booking::where('user_id', $user->id)
            ->join('room_contents', 'bookings.room_id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->select(
                'bookings.*',
                'room_contents.title as boat_name'
            )
            ->orderBy('bookings.created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Submit a Review for a Boat
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000'
        ]);

        $user = auth()->user();

        // Check if user has a successful booking for this room
        $hasBooking = \App\Models\Booking::where('user_id', $user->id)
            ->where('room_id', $request->room_id)
            ->where('payment_status', 1) // Finished/Paid
            ->exists();

        // For testing/MVP purposes, let's allow review if payment_status is 0 or 1 
        // as long as it's not cancelled (2).
        if (!$hasBooking) {
             $hasBooking = \App\Models\Booking::where('user_id', $user->id)
                ->where('room_id', $request->room_id)
                ->where('payment_status', 0)
                ->exists();
        }

        if (!$hasBooking) {
            return response()->json(['success' => false, 'message' => 'You can only review boats you have booked'], 403);
        }

        $room = Room::findOrFail($request->room_id);

        $roomReview = new \App\Models\RoomReview();
        $roomReview->user_id = $user->id;
        $roomReview->room_id = $request->room_id;
        $roomReview->hotel_id = $room->hotel_id;
        $roomReview->rating = $request->rating;
        $roomReview->review = $request->review;
        $roomReview->save();

        // Update boat average rating
        $avgRating = \App\Models\RoomReview::where('room_id', $room->id)->avg('rating');
        $room->average_rating = round($avgRating, 1);
        $room->save();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $roomReview
        ]);
    }
}