<?php

namespace App\Http\Controllers\Admin\PerahuManagement;

use App\Http\Controllers\Controller;
use App\Models\RoomReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoomReviewController extends Controller
{
    public function index(Request $request)
    {
        $order_number = $request->order_number;

        $reviews = RoomReview::with(['userInfo', 'hotelRoom.room_content' => function($q) {
            $q->where('language_id', 1); // Default lang
        }])
        ->when($order_number, function($query, $order_number) {
            return $query->whereHas('booking', function($q) use ($order_number) {
                $q->where('order_number', 'like', '%' . $order_number . '%');
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(15);

        return view('admin.perahu.reviews', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = RoomReview::findOrFail($id);
        
        // Update room average rating after deletion
        $roomId = $review->room_id;
        $review->delete();

        $avgRating = RoomReview::where('room_id', $roomId)->avg('rating') ?? 0;
        \App\Models\Perahu::where('id', $roomId)->update(['average_rating' => $avgRating]);

        Session::flash('success', __('Review deleted successfully!'));
        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $review = RoomReview::findOrFail($id);
            $roomId = $review->room_id;
            $review->delete();

            $avgRating = RoomReview::where('room_id', $roomId)->avg('rating') ?? 0;
            \App\Models\Perahu::where('id', $roomId)->update(['average_rating' => $avgRating]);
        }

        Session::flash('success', __('Reviews deleted successfully!'));
        return response()->json(['status' => 'success'], 200);
    }
}
