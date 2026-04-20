<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\BookingPayment\BookingController;
use App\Models\Perahu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $Time = $request->session()->get('checkInTime');
        $Date = $request->session()->get('checkInDate');
        $adult = $request->session()->get('adult');
        $children = $request->session()->get('children');

        $room_id = $request->session()->get('room_id');
        $dayPackage = (int)$request->session()->get('day_package');
        $room = Room::findorfail($room_id);
        $vendor_id = $room->vendor_id;
        $hotel_id = $room->hotel_id;
        $preparation_time = $room->preparation_time;

        $meetTime = $this->getRoomMeetTimeByPackage($room, $dayPackage);
        $returnTime = $this->getRoomReturnTimeByPackage($room, $dayPackage);

        $checkInTime = date('H:i:s', strtotime($Time ?: $meetTime));
        $checkInDate = date('Y-m-d', strtotime($Date));
        $check_in_date_time = $checkInDate . ' ' . $checkInTime;

        $checkoutDate = Carbon::parse($checkInDate)->addDays(max(0, $dayPackage - 1))->format('Y-m-d');
        $checkoutTime = date('H:i:s', strtotime($returnTime ?: $checkInTime));
        $next_booking_time = date('H:i:s', strtotime($checkoutTime . " +$preparation_time min"));

        $check_out_date_time = $checkoutDate . ' ' . $next_booking_time;

        if ($request->session()->has('price')) {
            $priceId = $request->session()->get('price');
        } else {
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }

        $bookingProcess = new BookingController();

        // do calculation
        $calculatedData = $bookingProcess->calculation($request, $priceId);

        $currencyInfo = $this->getCurrencyInfo();

        $arrData = array(
            'check_in_time' => $checkInTime,
            'check_in_date' =>  $checkInDate,
            'check_out_date' => $checkoutDate,
            'check_out_time' =>  $checkoutTime,
            'check_in_date_time' =>  $check_in_date_time,
            'check_out_date_time' =>  $check_out_date_time,
            'vendor_id' =>  $vendor_id,
            'hotel_id' =>  $hotel_id,
            'room_id' =>  $room_id,
            'preparation_time' =>  $preparation_time,
            'next_booking_time' =>  $next_booking_time,
            'hour' =>  $dayPackage * 24,
            'adult' =>  $adult,
            'children' => $children,
            'booking_name' => $request['booking_name'],
            'booking_email' => $request['booking_email'],
            'booking_phone' => $request['booking_phone'],
            'booking_address' => $request['booking_address'],
            'age_confirmation' => $request->has('age_confirmation') ? 1 : 0,
            'additional_service' => $calculatedData['additional_service'],
            'service_details' => $calculatedData['service_details'],
            'roomPrice' => $calculatedData['roomPrice'],
            'serviceCharge' => $calculatedData['serviceCharge'],
            'total' => $calculatedData['total'],
            'discount' => $calculatedData['discount'],
            'tax' => $calculatedData['tax'],
            'grandTotal' => $calculatedData['grandTotal'],
            'currencyText' => $currencyInfo->base_currency_text,
            'currencyTextPosition' => $currencyInfo->base_currency_text_position,
            'currencySymbol' => $currencyInfo->base_currency_symbol,
            'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
            'paymentMethod' => 'Approval Flow',
            'gatewayType' => 'approval',
            'payment_status' => 0,
            'attachment' => null
        );

        // store product order information in database
        $bookingInfo = $bookingProcess->storeData($arrData);

        // Force order status to pending for approval flow
        $bookingInfo->update(['order_status' => 'pending']);

        // generate an invoice in pdf format 
        $invoice = $bookingProcess->generateInvoice($bookingInfo);

        // then, update the invoice field info in database 
        $bookingInfo->update(['invoice' => $invoice]);

        // send a mail to the vendor with the invoice
        $bookingProcess->prepareMailForvendor($bookingInfo);

        // Send booking requested email to the user
        try {
            \Illuminate\Support\Facades\Mail::to($bookingInfo->booking_email)
                ->send(new \App\Mail\BookingRequestedMail($bookingInfo));
        } catch (\Exception $e) {
            // Log error or ignore if mail fails
            \Illuminate\Support\Facades\Log::error('Failed to send booking requested email: ' . $e->getMessage());
        }

        // remove all session data
        $request->session()->forget('price');
        $request->session()->forget('checkInTime');
        $request->session()->forget('checkInDate');
        $request->session()->forget('adult');
        $request->session()->forget('children');
        $request->session()->forget('roomDiscount');
        $request->session()->forget('takeService');
        $request->session()->forget('serviceCharge');
        $request->session()->forget('room_id');
        $request->session()->forget('day_package');
        $request->session()->forget('room_price');
        $request->session()->forget('checkOutDate');
        $request->session()->forget('checkOutTime');

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'approval_booking']);
    }

    private function getRoomMeetTimeByPackage(Room $room, int $dayPackage): ?string
    {
        if ($dayPackage === 1) {
            return $room->meet_time_day_1;
        }
        if ($dayPackage === 2) {
            return $room->meet_time_day_2;
        }
        if ($dayPackage === 3) {
            return $room->meet_time_day_3;
        }
        return null;
    }

    private function getRoomReturnTimeByPackage(Room $room, int $dayPackage): ?string
    {
        if ($dayPackage === 1) {
            return $room->return_time_day_1;
        }
        if ($dayPackage === 2) {
            return $room->return_time_day_2;
        }
        if ($dayPackage === 3) {
            return $room->return_time_day_3;
        }
        return null;
    }
}
