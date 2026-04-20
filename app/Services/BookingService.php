<?php

namespace App\Services;

use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\PaymentGateway\OfflineGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    /**
     * Store booking details in session.
     */
    public function storeBookingToSession(array $data)
    {
        Session::put('room_id', $data['room_id']);
        Session::put('day_package', $data['day_package']);
        Session::put('room_price', $data['room_price']);
        Session::put('checkInTime', $data['checkInTime']);
        Session::put('checkInDate', $data['checkInDate']);
        Session::put('checkOutTime', $data['checkOutTime'] ?? null);
        Session::put('checkOutDate', $data['checkOutDate']);
        Session::put('price', $data['day_package']); // Legacy key
        Session::put('adult', $data['adult']);
        Session::put('children', $data['children'] ?? 0);
        
        Session::forget('serviceCharge');
        Session::forget('takeService');
        Session::forget('roomDiscount');
        Session::forget('couponCode');
    }

    /**
     * Get checkout data from session.
     */
    public function getCheckoutData()
    {
        return [
            'roomId' => Session::get('room_id'),
            'dayPackage' => Session::get('day_package'),
            'roomPrice' => Session::get('room_price'),
            'checkInTime' => Session::get('checkInTime'),
            'checkInDate' => Session::get('checkInDate'),
            'checkOutTime' => Session::get('checkOutTime'),
            'checkOutDate' => Session::get('checkOutDate'),
            'adult' => Session::get('adult'),
            'children' => Session::get('children'),
            'authUser' => Auth::guard('web')->user()
        ];
    }

    /**
     * Get available payment gateways.
     */
    public function getPaymentGateways()
    {
        $onlineGateways = OnlineGateway::where('status', 1)->get()->map(function($gateway) {
            if ($gateway->keyword == 'midtrans') {
                $gateway->name = 'QRIS';
            }
            return $gateway;
        })->sortByDesc(function($gateway) {
            return $gateway->keyword == 'midtrans' ? 2 : ($gateway->keyword == 'xendit' ? 1 : 0);
        });

        $offlineGateways = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

        return [
            'onlineGateways' => $onlineGateways,
            'offlineGateways' => $offlineGateways
        ];
    }
}
