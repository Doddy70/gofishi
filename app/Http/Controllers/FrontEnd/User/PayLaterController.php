<?php

namespace App\Http\Controllers\FrontEnd\User;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PaymentGateway\OnlineGateway;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use App\Actions\Booking\ProcessPaidBooking;
use App\Models\BasicSettings\Basic;
use App\Models\Vendor;

class PayLaterController extends Controller
{
    public function __construct(
        protected ProcessPaidBooking $processPaidBooking
    ) {}

    public function midtrans(Request $request, $id)
    {
        $bookingInfo = Booking::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($bookingInfo->payment_status != 0 || $bookingInfo->order_status != 'approved') {
            return redirect()->back()->with('error', 'Booking is not eligible for payment.');
        }

        $data = OnlineGateway::whereKeyword('midtrans')->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Midtrans is not configured.');
        }
        $midtransData = json_decode($data->information, true);

        MidtransConfig::$serverKey = $midtransData['server_key'];
        MidtransConfig::$isProduction = ($midtransData['midtrans_mode'] == 0);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $bookingInfo->order_number . '-' . time(),
                'gross_amount' => (int)$bookingInfo->grand_total, 
            ],

            'customer_details' => [
                'email' =>  $bookingInfo->booking_email,
                'phone' => $bookingInfo->booking_phone,
                'name' =>  $bookingInfo->booking_name,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }

        $request->session()->put('pay_later_booking_id', $bookingInfo->id);

        $websiteInfo = \App\Models\BasicSettings\Basic::select('website_title')->first();

        return view('frontend.payment.pay-later-midtrans', [
            'snapToken' => $snapToken, 
            'data' => $midtransData, 
            'bookingInfo' => $bookingInfo,
            'websiteInfo' => $websiteInfo
        ]);
    }

    public function midtransNotify(Request $request)
    {
        $booking_id = $request->session()->get('pay_later_booking_id');
        if (!$booking_id) {
            return redirect()->route('user.perahu_bookings')->with('error', 'Payment session expired.');
        }

        $bookingInfo = Booking::find($booking_id);
        if ($bookingInfo && $bookingInfo->payment_status == 0) {
            // Using unified action for paid booking processing
            $this->processPaidBooking->execute($bookingInfo, 'TRX-' . time());
        }

        $request->session()->forget('pay_later_booking_id');

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'online']);
    }
}