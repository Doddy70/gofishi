<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomImage;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelContent;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HostController extends Controller
{
    /**
     * List Host's Boats
     */
    public function index(Request $request)
    {
        $vendor = auth()->user();
        $langCode = $request->header('Accept-Language', 'id');
        $language = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first();

        $boats = Room::where('vendor_id', $vendor->id)
            ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->select('rooms.*', 'room_contents.title', 'room_contents.slug')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $boats
        ]);
    }

    /**
     * Create New Boat
     */
    public function store(Request $request)
    {
        $vendor = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'hotel_id' => 'required|exists:hotels,id',
            'price_day_1' => 'required|numeric',
            'price_day_2' => 'nullable|numeric',
            'price_day_3' => 'nullable|numeric',
            'max_guests' => 'required|integer',
            'feature_image' => 'required|image|max:1024', // 1MB Limit
            'gallery_images.*' => 'image|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Handle Feature Image
        $featureImageName = time() . '_' . Str::random(10) . '.' . $request->file('feature_image')->extension();
        $request->file('feature_image')->move(public_path('assets/img/rooms/'), $featureImageName);

        // Create Room
        $room = new Room();
        $room->vendor_id = $vendor->id;
        $room->hotel_id = $request->hotel_id;
        $room->feature_image = $featureImageName;
        $room->price_day_1 = $request->price_day_1;
        $room->price_day_2 = $request->price_day_2;
        $room->price_day_3 = $request->price_day_3;
        $room->adult = $request->max_guests;
        $room->status = 1;
        
        // Default Tiered Times if not provided
        $room->meet_time_day_1 = $request->meet_time_day_1 ?? '08:00';
        $room->return_time_day_1 = $request->return_time_day_1 ?? '17:00';
        $room->meet_time_day_2 = $request->meet_time_day_2 ?? '08:00';
        $room->return_time_day_2 = $request->return_time_day_2 ?? '20:00';
        $room->meet_time_day_3 = $request->meet_time_day_3 ?? '08:00';
        $room->return_time_day_3 = $request->return_time_day_3 ?? '22:00';

        $room->save();

        // Create Room Content (Localized)
        $languages = Language::all();
        foreach ($languages as $lang) {
            $content = new RoomContent();
            $content->room_id = $room->id;
            $content->language_id = $lang->id;
            $content->title = $request->title;
            $content->slug = Str::slug($request->title); // Use Laravel Str::slug
            $content->description = $request->description;
            $content->save();
        }

        // Handle Gallery Images (Max 10 total)
        if ($request->hasFile('gallery_images')) {
            $count = 0;
            foreach ($request->file('gallery_images') as $file) {
                if ($count >= 10) break;
                $imgName = time() . '_' . Str::random(10) . '.' . $file->extension();
                $file->move(public_path('assets/img/rooms/gallery/'), $imgName);
                
                $roomImage = new RoomImage();
                $roomImage->room_id = $room->id;
                $roomImage->image = $imgName;
                $roomImage->save();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Boat created successfully',
            'data' => $room
        ], 201);
    }

    /**
     * List Bookings for Host's Boats
     */
    public function bookings(Request $request)
    {
        $vendor = auth()->user();
        $langCode = $request->header('Accept-Language', 'id');
        $language = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first();

        $bookings = Booking::where('bookings.vendor_id', $vendor->id)
            ->join('room_contents', 'bookings.room_id', '=', 'room_contents.room_id')
            ->where('room_contents.language_id', $language->id)
            ->select('bookings.*', 'room_contents.title as boat_name')
            ->orderBy('bookings.created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Accept a Booking
     */
    public function acceptBooking($id)
    {
        $vendor = auth()->user();
        $booking = Booking::where('id', $id)->where('vendor_id', $vendor->id)->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        if ($booking->payment_status != 0) {
            return response()->json(['success' => false, 'message' => 'Booking is already processed'], 422);
        }

        $booking->payment_status = 0; // Remains pending until payment, but we mark it as "accepted" internally or via order_status if we had it.
        // Since we don't have order_status, we might use payment_status = 3 for "Accepted, awaiting payment" if we want to distinguish.
        // However, the original code uses 0 for pending. Let's send the mail.
        
        try {
            \Illuminate\Support\Facades\Mail::to($booking->booking_email)->send(new \App\Mail\BookingAcceptedMail($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Accept Mail Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking accepted. Confirmation email sent to user.'
        ]);
    }

    /**
     * Reject a Booking
     */
    public function rejectBooking($id)
    {
        $vendor = auth()->user();
        $booking = Booking::where('id', $id)->where('vendor_id', $vendor->id)->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $booking->payment_status = 2; // Rejected/Cancelled
        $booking->save();

        try {
            \Illuminate\Support\Facades\Mail::to($booking->booking_email)->send(new \App\Mail\BookingRejectedMail($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Reject Mail Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking rejected successfully'
        ]);
    }
}
