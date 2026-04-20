<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\RoomReview;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    /**
     * Display all reviews for this vendor's boats
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $reviews = RoomReview::with(['room.room_content', 'user'])
            ->whereHas('room', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->latest()
            ->paginate(10);

        return view('vendors.perahu.reviews', compact('reviews'));
    }

    /**
     * Analyze sentiment of recent reviews via Gemini AI
     */
    public function analyzeSentiment(Request $request, \App\Services\SmartAiService $aiService)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $recentReviews = RoomReview::whereHas('room', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->whereNotNull('review')
            ->latest()
            ->take(20)
            ->pluck('review')
            ->toArray();

        if (count($recentReviews) === 0) {
            return response()->json(['error' => 'Tidak ada ulasan murni untuk dianalisis.'], 400);
        }

        try {
            $jsonResponse = $aiService->analyzeReviewsSentiment($recentReviews);
            $parsed = json_decode($jsonResponse, true);

            if (!$parsed || !isset($parsed['sentiment_score'])) {
                $jsonResponse = str_replace('```json', '', $jsonResponse);
                $jsonResponse = str_replace('```', '', $jsonResponse);
                $parsed = json_decode(trim($jsonResponse), true);
                if (!$parsed) throw new \Exception("AI menolak merespons dalam format JSON murni.");
            }

            return response()->json([
                'status' => 'success',
                'data' => $parsed
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Sentiment AI Error: " . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses analisis AI. ('.$e->getMessage().')'], 500);
        }
    }

    /**
     * Store a reply to a user's review
     */
    public function replyToRoomReview(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string|max:1000']);

        $vendor = Auth::guard('vendor')->user();
        $review = RoomReview::where('id', $id)
            ->whereHas('room', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->firstOrFail();

        $review->update(['reply' => $request->reply]);

        Session::flash('success', __('Berhasil mengirim tanggapan!'));
        return redirect()->back();
    }

    /**
     * Store a vendor's review for a user
     */
    public function storeUserReview(Request $request)
    {
        // the ID passed is usually booking ID, let's treat it as booking_id
        $booking_id = $request->booking_id;

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $vendor = Auth::guard('vendor')->user();

        // Ensure this booking belongs to this vendor
        $booking = Booking::where('id', $booking_id)->where('vendor_id', $vendor->id)->firstOrFail();

        // Ensure review doesn't already exist for this booking by this vendor
        $existingReview = UserReview::where('booking_id', $booking->id)->where('vendor_id', $vendor->id)->first();
        if ($existingReview) {
            return redirect()->back()->with('error', __('You have already submitted a review for this booking.'));
        }

        UserReview::create([
            'user_id' => $request->user_id,
            'vendor_id' => $vendor->id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return redirect()->back()->with('success', __('Review submitted successfully.'));
    }
}