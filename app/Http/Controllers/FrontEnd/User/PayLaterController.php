<?php

namespace App\Http\Controllers\FrontEnd\User;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PaymentGateway\OnlineGateway;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use App\Http\Controllers\FrontEnd\BookingPayment\BookingController;

class PayLaterController extends Controller
{
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
                'gross_amount' => intval($bookingInfo->grand_total) * 1000, 
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
            return redirect()->route('user.room_bookings')->with('error', 'Payment session expired.');
        }

        $bookingInfo = Booking::find($booking_id);
        if ($bookingInfo) {
            $bookingInfo->update(['payment_status' => 1]);

            // generate an invoice in pdf format 
            $bookingProcess = new BookingController();
            $invoice = $bookingProcess->generateInvoice($bookingInfo);
            $bookingInfo->update(['invoice' => $invoice]);

            // send a mail to the customer with the invoice
            $bookingProcess->prepareMailForCustomer($bookingInfo);
            $bookingProcess->prepareMailForvendor($bookingInfo);

            // Handle vendor balance logic
            $vendor_id = $bookingInfo->vendor_id;
            if ($vendor_id == 0) {
                $commission = $bookingInfo->grand_total;
            } else {
                $commission = 0;
            }
            $vendor = \App\Models\Vendor::find($vendor_id);
            $earning = \App\Models\BasicSettings\Basic::first();
            if ($vendor_id == 0) {
                $earning->total_earning += $bookingInfo->grand_total;
            } else {
                $earning->total_earning += $commission;
            }
            $earning->save();

            if ($vendor) {
                $pre_balance = $vendor->amount;
                $vendor->amount += ($bookingInfo->grand_total - $commission);
                $vendor->save();
                $after_balance = $vendor->amount;
            } else {
                $after_balance = NULL;
                $pre_balance = NULL;
            }

            $data = [
                'transcation_id' => time(),
                'booking_id' => $bookingInfo->id,
                'transcation_type' => 'room_booking',
                'user_id' => $bookingInfo->user_id,
                'vendor_id' => $vendor_id,
                'payment_status' => 1,
                'payment_method' => $bookingInfo->payment_method,
                'grand_total' => $bookingInfo->grand_total,
                'commission' => $commission,
                'pre_balance' => $pre_balance,
                'after_balance' => $after_balance,
                'gateway_type' => $bookingInfo->gateway_type,
                'currency_symbol' => $bookingInfo->currency_symbol,
                'currency_symbol_position' => $bookingInfo->currency_symbol_position,
            ];
            store_transaction($data);
        }

        $request->session()->forget('pay_later_booking_id');

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'online']);
    }
}