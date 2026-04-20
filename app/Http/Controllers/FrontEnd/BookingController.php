<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BoatPackage;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Perahu;
use App\Models\RoomContent;
use App\Models\RoomCoupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function checkCheckout(Request $request)
    {
        $rule = [
            'room_id' => 'required|integer',
            'package_id' => 'required|integer',
            'checkInDate' => 'required|date_format:Y-m-d',
        ];

        $messages = [
            'package_id.required' => __('Please select a package first.'),
            'room_id.required' => __('Please select a boat first.'),
            'checkInDate.required' => __('Please select a start date.'),
        ];

        $validator = Validator::make($request->all(), $rule, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $room = Perahu::findOrFail($request->room_id);
        $package = BoatPackage::findOrFail($request->package_id);

        // Verify package belongs to the room and is active
        if ($package->room_id != $room->id || $package->status != 1) {
            return redirect()->back()->with('error', __('The selected package is not valid for this boat.'));
        }

        // DIAGNOSTIC LOGGING
        \Illuminate\Support\Facades\Log::channel('single')->info('BookingController::checkCheckout - Before storing session', [
            'timestamp' => now()->toDateTimeString(),
            'session_id' => $request->getSession()->getId(),
            'room_id' => $room->id,
            'package_id' => $package->id,
            'checkInDate' => $request->checkInDate,
        ]);

        $request->session()->put('room_id', $room->id);
        $request->session()->put('package_id', $package->id);
        $request->session()->put('day_package', $package->duration_days);
        $request->session()->put('room_price', $package->price);
        $request->session()->put('checkInTime', $package->meeting_time);
        $request->session()->put('checkInDate', $request->checkInDate);
        $request->session()->put('checkOutTime', $package->return_time);
        $request->session()->put('checkOutDate', Carbon::parse($request->checkInDate)->addDays(max(0, $package->duration_days - 1))->format('m/d/Y'));
        
        // These seem legacy, but let's keep them for now to avoid breaking checkout view
        $request->session()->put('price', $package->price);
        $request->session()->put('adult', $request->adult ?? $room->adult); 
        $request->session()->put('children', $request->children ?? 0);

        Session::forget('serviceCharge');
        Session::forget('takeService');
        Session::forget('roomDiscount');
        Session::forget('couponCode');

        return redirect()->route('frontend.perahu.checkout');
    }

    public function checkout(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $roomId = $request->session()->get('room_id');
        $packageId = $request->session()->get('package_id');

        // DIAGNOSTIC LOGGING
        \Illuminate\Support\Facades\Log::channel('single')->info('BookingController::checkout - Session state', [
            'timestamp' => now()->toDateTimeString(),
            'session_id' => $request->getSession()->getId(),
            'room_id' => $roomId,
            'package_id' => $packageId,
            'checkout_redirect' => $request->session()->get('checkout_redirect'),
            'checkout_return_url' => $request->session()->get('checkout_return_url'),
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'all_session_keys' => array_keys($request->session()->all()),
        ]);

        if (empty($roomId) || empty($packageId)) {
            \Illuminate\Support\Facades\Log::channel('single')->warning('BookingController::checkout - Missing room_id or package_id, redirecting to perahu', [
                'room_id' => $roomId,
                'package_id' => $packageId,
                'session_keys' => array_keys($request->session()->all()),
            ]);
            return redirect(route('frontend.perahu'));
        }
        
        $information['language'] = $language;
        $information['pageHeading'] = $misc->getPageHeading($language);
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['authUser'] = Auth::guard('web')->user();

        // Persist checkout continuation target so auth flows can reliably
        // return to checkout step 2 even if referrer/old input changes.
        $request->session()->put('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
        if (!$information['authUser']) {
            $request->session()->put('checkout_redirect', true);
        }
        $information['currencyInfo'] = $this->getCurrencyInfo();

        $information['room'] = Perahu::findOrFail($roomId);
        $information['package'] = BoatPackage::findOrFail($packageId);

        $information['checkInDate']  = $request->session()->get('checkInDate');
        $information['checkOutDate'] = $request->session()->get('checkOutDate');

        // Payment Gateways
        $information['onlineGateways'] = OnlineGateway::where('status', 1)->get()->map(function($gateway) {
            if ($gateway->keyword == 'midtrans') $gateway->name = 'QRIS';
            return $gateway;
        })->sortByDesc(fn($g) => $g->keyword == 'midtrans' ? 2 : ($g->keyword == 'xendit' ? 1 : 0));
        
        $information['offline_gateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

        // Additional Services for the boat
        $room = $information['room'];
        $additional_services = [];
        $services_attr = $room->additional_service;
        if (is_string($services_attr)) {
            $services_attr = json_decode($services_attr, true);
        }

        if (!empty($services_attr) && is_array($services_attr)) {
            $service_ids = array_keys($services_attr);
            $additional_services = \App\Models\AdditionalService::join('additional_service_contents', 'additional_services.id', '=', 'additional_service_contents.additional_service_id')
                ->whereIn('additional_services.id', $service_ids)
                ->where('additional_service_contents.language_id', $language->id)
                ->select('additional_services.id', 'additional_service_contents.title')
                ->get()
                ->map(function($service) use ($services_attr) {
                    $service->price = $services_attr[$service->id] ?? 0;
                    return $service;
                });
        }
        $information['additional_services'] = $additional_services;

        return view('frontend.perahu.checkout', $information);
    }

    public function applyCoupon(Request $request)
    {
        try {
            $coupon = RoomCoupon::where('code', $request->coupon)->firstOrFail();

            $startDate = Carbon::parse($coupon->start_date);
            $endDate = Carbon::parse($coupon->end_date);
            $todayDate = Carbon::now();

            if ($todayDate->between($startDate, $endDate) == false) {
                return response()->json(['error' => __('Sorry, coupon has been expired!')]);
            }

            $roomId = $request->session()->get('room_id');
            $serviceCharge = $request->session()->get('serviceCharge');
            $price = $request->session()->get('room_price');

            $roomIds = empty($coupon->rooms) ? '' : json_decode($coupon->rooms);

            if (!empty($roomIds) && !in_array($roomId, $roomIds)) {
                return response()->json(['error' => __('You can not apply this coupon for this perahu!')]);
            }

            session()->put('couponCode', $request->coupon);

            if ($coupon->type == 'fixed') {

                $request->session()->put('roomDiscount', $coupon->value);
                return response()->json([
                    'success' => __('Coupon applied successfully.'),
                ]);
            } else {

                $couponAmount = ($price + $serviceCharge) * ($coupon->value / 100);
                $request->session()->put('roomDiscount', $couponAmount);

                return response()->json([
                    'success' => __('Coupon applied successfully.'),
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => __('Coupon is not valid!')]);
        }
    }

    public function additonalService(Request $request)
    {
        $haven = Session::get('takeService');
        $taken = $request->takeService;

        $havenArray = !empty($haven) ? explode(',', $haven) : [];
        $takenArray = !empty($taken) ? explode(',', $taken) : [];

        $havenCount = count($havenArray);
        $takenCount = count($takenArray);

        Session::put('serviceCharge', $request->serviceCharge);
        Session::put('takeService', $request->takeService);
        Session::forget('roomDiscount');
        Session::forget('couponCode');


        if ($havenCount > $takenCount) {
            return response()->json([
                'error' => __('Additional Service removed successfully.'),
            ]);
        } else {
            return response()->json([
                'success' => __('Additional Service added successfully.'),
            ]);
        }
    }
}
