<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Perahu;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Get Overall Statistics (Tier 3+)
     */
    public function dashboard(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => User::count(),
                'total_vendors' => Vendor::count(),
                'total_boats' => Room::count(),
                'total_bookings' => Booking::count(),
                'pending_bookings' => Booking::where('payment_status', 0)->count()
            ]
        ]);
    }

    /**
     * Managed All Boats (Tier 2+)
     */
    public function manageBoats(Request $request)
    {
        $boats = Room::with('vendor')
            ->select('rooms.*')
            ->orderBy('id', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $boats
        ]);
    }

    /**
     * Approve/Reject/Suspend Vendor (Tier 1 Only)
     */
    public function manageVendor(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2', // 0 = Pending, 1 = Approved, 2 = Suspended
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $vendor->status = $request->status;
        $vendor->save();

        return response()->json([
            'success' => true,
            'message' => 'Vendor status updated successfully'
        ]);
    }

    /**
     * Moderate Booking Content/Status (Tier 2+)
     */
    public function moderateBooking(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking->payment_status = $request->payment_status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking moderated successfully'
        ]);
    }
}
