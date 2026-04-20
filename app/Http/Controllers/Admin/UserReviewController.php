<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserReview;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = UserReview::with(['user', 'vendor', 'booking'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = UserReview::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', __('Review deleted successfully!'));
    }
}