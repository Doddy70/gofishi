<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FrontEnd\BookingPayment\OfflineController;
use App\Http\Controllers\FrontEnd\BookingPayment\PayPalController;
use App\Http\Controllers\FrontEnd\BookingPayment\InstamojoController;
use App\Http\Controllers\FrontEnd\BookingPayment\RazorpayController;
use App\Http\Controllers\FrontEnd\BookingPayment\PaytmController;
use App\Http\Controllers\FrontEnd\BookingPayment\PaystackController;
use App\Http\Controllers\FrontEnd\BookingPayment\MercadoPagoController;
use App\Http\Controllers\FrontEnd\BookingPayment\MidtransController;
use App\Http\Controllers\FrontEnd\BookingPayment\MyfatoorahController;
use App\Http\Controllers\FrontEnd\BookingPayment\YocoController;
use App\Http\Controllers\FrontEnd\BookingPayment\ToyyibpayController;
use App\Http\Controllers\FrontEnd\BookingPayment\ApprovalController;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Requests\Room\BookingProcessRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Helpers\BasicMailer;
use App\Models\AdditionalService;
use App\Models\BookingHour;
use App\Models\Membership;
use App\Models\Perahu;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Services\NotificationService;

class BookingController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService = null)
    {
        $this->notificationService = $notificationService ?? app(NotificationService::class);
    }

    public function index(BookingProcessRequest $request)
    {
        if (!$request->exists('gateway')) {
            Session::flash('error', 'Please select a payment method.');

            return redirect()->back()->withInput();
        }
        if ($request['gateway'] == 'paypal') {
            $paypal = app()->make(PayPalController::class);

            return $paypal->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'midtrans') {
            $midtrans = app()->make(MidtransController::class);
            $userType = 'booking';
            return $midtrans->index($request, 'Room Booking', $userType);
        } else if ($request['gateway'] == 'instamojo') {
            $instamojo = new InstamojoController();

            return $instamojo->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'paystack') {
            $paystack = new PaystackController();

            return $paystack->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'flutterwave') {
            $flutterwave = new FlutterwaveController();

            return $flutterwave->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'iyzico') {

            $iyzico = new IyzicoController();
            return $iyzico->index($request, 'product purchase');
        } else if ($request['gateway'] == 'toyyibpay') {

            $toyyibpay = new ToyyibpayController();
            return $toyyibpay->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'razorpay') {
            $razorpay = new RazorpayController();

            return $razorpay->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'paytabs') {

            $paytabs = new PaytabsController();
            return $paytabs->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'phonepe') {

            $phonepe = new PhonepeController();
            return $phonepe->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'yoco') {

            $yoco = new YocoController();
            return $yoco->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'mercadopago') {
            $mercadopago = new MercadoPagoController();

            return $mercadopago->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'mollie') {
            $mollie = new MollieController();

            return $mollie->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'stripe') {
            $stripe = new StripeController();

            return $stripe->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'myfatoorah') {
            $myfatoorah = new MyfatoorahController();
            return $myfatoorah->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'paytm') {
            $paytm = new PaytmController();

            return $paytm->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'perfect_money') {
            $perfect_money = new PerfectMoneyController();

            return $perfect_money->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'xendit') {

            $xendit = app()->make(XenditController::class);
            return $xendit->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'authorize.net') {
            $author = new AuthorizeNetController();

            return $author->index($request, 'Room Booking');
        } else if ($request['gateway'] == 'approval') {
            $approval = new ApprovalController();
            return $approval->index($request);
        } else {
            $offline = app()->make(OfflineController::class);

            return $offline->index($request, 'Room Booking');
        }
    }
    public function timeCheck(Request $request, $paymentMethod)
    {
        $Time = $request->session()->get('checkInTime');
        $Date = $request->session()->get('checkInDate');
        $adult = $request->session()->get('adult');
        $children = $request->session()->get('children');

        $room_id = $request->session()->get('room_id');
        $package_id = $request->session()->get('package_id');
        $dayPackage = (int)$request->session()->get('day_package');
        
        $room = Perahu::findOrFail($room_id);
        $package = \App\Models\BoatPackage::find($package_id);
        
        $vendor_id = $room->vendor_id;
        $hotel_id = $room->hotel_id;
        $preparation_time = $room->preparation_time;

        // Use package data if available, fallback to legacy room columns
        $meetTime = $package ? $package->meeting_time : $this->getRoomMeetTimeByPackage($room, $dayPackage);
        $returnTime = $package ? $package->return_time : $this->getRoomReturnTimeByPackage($room, $dayPackage);

        $checkInDateTime = Carbon::parse($Date . ' ' . ($Time ?: $meetTime));
        $checkInDate = $checkInDateTime->toDateString();
        $checkInTime = $checkInDateTime->toTimeString();

        // Calculate checkout based on day package
        $checkoutDateTime = (clone $checkInDateTime)->addDays(max(0, $dayPackage - 1));
        if ($returnTime) {
            $checkoutDateTime = Carbon::parse($checkoutDateTime->toDateString() . ' ' . $returnTime);
        }

        $checkOutDate = $checkoutDateTime->toDateString();
        $checkOutTime = $checkoutDateTime->toTimeString();

        // Final buffer for the next booking (Preparation time)
        $nextBookingDateTime = (clone $checkoutDateTime)->addMinutes($preparation_time);
        $nextBookingTime = $nextBookingDateTime->toTimeString();

        $priceId = $request->session()->get('price');
        $calculatedData = $this->calculation($request, $priceId);
        $currencyInfo = $this->getCurrencyInfo();

        $arrData = array(
            'check_in_time' => $checkInTime,
            'check_in_date' =>  $checkInDate,
            'check_out_date' => $checkOutDate,
            'check_out_time' =>  $checkOutTime,
            'check_in_date_time' =>  $checkInDateTime->toDateTimeString(),
            'check_out_date_time' =>  $nextBookingDateTime->toDateTimeString(),
            'vendor_id' =>  $vendor_id,
            'hotel_id' =>  $hotel_id,
            'room_id' =>  $room_id,
            'package_id' => $package_id,
            'preparation_time' =>  $preparation_time,
            'next_booking_time' =>  $nextBookingTime,
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
            'paymentMethod' => $paymentMethod,
            'gatewayType' => 'online',
            'payment_status' => $paymentMethod == 'Iyzico' ? 0 : 1,
            'attachment' => null,
        );

        return $arrData;
    }
    public function calculation(Request $request, $priceId)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $roomId = $request->session()->get('room_id');
        $packageId = $request->session()->get('package_id');
        $dayPackage = (int)$request->session()->get('day_package');
        
        $room = Perahu::find($roomId);
        $package = \App\Models\BoatPackage::find($packageId);
        
        $roomPrice = 0;

        if ($package) {
            $roomPrice = $package->price;
        } elseif ($room) {
            // Fallback for legacy items without linked package
            if ($dayPackage == 1) $roomPrice = $room->price_day_1;
            elseif ($dayPackage == 2) $roomPrice = $room->price_day_2;
            elseif ($dayPackage == 3) $roomPrice = $room->price_day_3;
            else $roomPrice = $room->price_day_1;
        }

        if (empty($roomId)) {
            $roomPrice = floatval(0);
        }

        // Handle Services from Request or Session
        if ($request->has('services')) {
            $selectedServices = $request->services; // array of IDs
            $serviceCharge = 0;
            $services_attr = $room->additional_service;
            if (is_string($services_attr)) {
                $services_attr = json_decode($services_attr, true);
            }
            if (!empty($services_attr) && is_array($services_attr)) {
                foreach ($selectedServices as $sId) {
                    if (isset($services_attr[$sId])) {
                        $serviceCharge += $services_attr[$sId];
                    }
                }
            }
            $request->session()->put('serviceCharge', $serviceCharge);
            $request->session()->put('takeService', implode(',', $selectedServices));
        }

        $serviceCharge = floatval($request->session()->get('serviceCharge'));
        $total = $roomPrice + $serviceCharge;
        $service_details = [];

        if ($request->session()->has('roomDiscount')) {
            $discountVal = $request->session()->get('roomDiscount');
        }

        if ($request->session()->has('takeService') && !empty($roomId)) {
            $additional_service = $request->session()->get('takeService');

            $room = Perahu::find($roomId);
            $services_attr = $room->additional_service;
            if (is_string($services_attr)) {
                $services_attr = json_decode($services_attr, true);
            }
            $additionalServices = is_array($services_attr) ? $services_attr : [];

            $service_ids = explode(',', $additional_service);

            foreach ($service_ids as $id) {
                if (isset($additionalServices[$id])) {
                    $price = $additionalServices[$id];

                    $service = AdditionalService::join('additional_service_contents', 'additional_services.id', '=', 'additional_service_contents.additional_service_id')
                        ->where('additional_services.id', $id)
                        ->where('additional_service_contents.language_id', $language->id)
                        ->select('additional_service_contents.title')
                        ->first();
                    if ($service) {
                        $service_details[] = [
                            'price' => $price,
                            'service_name' => $service->title,
                        ];
                    }
                }
            }
        } else {
            $additional_service = null;
        }

        $discount = isset($discountVal) ? floatval($discountVal) : 0.00;
        $subtotal = $total - $discount;

        $taxData = Basic::select('hotel_tax_amount')->first();

        $taxAmount = floatval($taxData->hotel_tax_amount);
        $calculatedTax = $subtotal * ($taxAmount / 100);
        $grandTotal = $subtotal + $calculatedTax;

        $calculatedData = array(
            'total' => $total,
            'discount' => $discount,
            'subtotal' => $subtotal,
            'tax' => $calculatedTax,
            'roomPrice' => $roomPrice,
            'serviceCharge' => $serviceCharge,
            'grandTotal' => $grandTotal,
            'additional_service' => $additional_service,
            'service_details' => $service_details,
        );

        return $calculatedData;
    }
    private function getRoomMeetTimeByPackage(Perahu $room, int $dayPackage): ?string
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

    private function getRoomReturnTimeByPackage(Perahu $room, int $dayPackage): ?string
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

    public function storeData($arrData)
    {
        return DB::transaction(function () use ($arrData) {
            // ATOMIC CHECK: Lock the room row to prevent concurrent modifications
            $room = Perahu::where('id', $arrData['room_id'])->lockForUpdate()->firstOrFail();
            
            // Re-verify availability exactly at the moment of booking
            // Robust clash detection: Any existing booking that overlaps with requested time
            $existingBookings = Booking::where('room_id', $room->id)
                ->where(function ($q) {
                    $q->where('payment_status', 1) 
                      ->orWhere(function ($sq) {
                          $sq->where('payment_status', 0)->where('gateway_type', 'offline'); 
                      })
                      ->orWhere(function ($sq) {
                          $sq->where('payment_status', 0)->where('gateway_type', 'online')->where('created_at', '>=', \Carbon\Carbon::now()->subMinutes(30));
                      });
                })
                ->where(function ($q) use ($arrData) {
                    // Standard Overlap Logic: (StartA < EndB) AND (EndA > StartB)
                    $q->where('check_in_date_time', '<', $arrData['check_out_date_time'])
                      ->where('check_out_date_time', '>', $arrData['check_in_date_time']);
                })
                ->count();

            if ($existingBookings >= $room->number_of_rooms_of_this_same_type) {
                \Illuminate\Support\Facades\Log::warning("Booking Clash Detected", [
                    'room_id' => $room->id,
                    'requested_start' => $arrData['check_in_date_time'],
                    'requested_end' => $arrData['check_out_date_time'],
                    'existing_count' => $existingBookings,
                    'limit' => $room->number_of_rooms_of_this_same_type,
                    'email' => $arrData['booking_email']
                ]);
                throw new \Exception(__('Maaf, armada sudah penuh untuk jadwal ini. Silakan pilih waktu lain.'));
            }

            // Determine initial order status based on booking type
            // If approval is needed, status is 'pending'
            // If direct, status is 'approved' (but payment still needs to be confirmed)
            $orderStatus = ($room->booking_type == 'approval') ? 'pending' : 'approved';

            $orderNumber = $arrData['order_number'] ?? (Carbon::now()->format('ymdHis') . Str::upper(Str::random(4)));

            $booking = Booking::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : null,
                'check_in_time' => $arrData['check_in_time'],
                'check_in_date' => $arrData['check_in_date'],
                'check_out_date' => $arrData['check_out_date'],
                'check_out_time' => $arrData['check_out_time'],
                'check_in_date_time' => $arrData['check_in_date_time'],
                'check_out_date_time' => $arrData['check_out_date_time'],
                'vendor_id' => $arrData['vendor_id'],
                'hotel_id' => $arrData['hotel_id'],
                'room_id' => $arrData['room_id'],
                'preparation_time' => $arrData['preparation_time'],
                'next_booking_time' => $arrData['next_booking_time'],
                'hour' => $arrData['hour'],
                'adult' => $arrData['adult'],
                'children' => $arrData['children'],
                'booking_name' => $arrData['booking_name'],
                'booking_email' => $arrData['booking_email'],
                'booking_phone' => $arrData['booking_phone'],
                'booking_address' => $arrData['booking_address'],
                'age_confirmed' => isset($arrData['age_confirmation']) ? 1 : 0,
                'additional_service' => json_encode($arrData['additional_service']),
                'service_details' => json_encode($arrData['service_details']),
                'roomPrice' => $arrData['roomPrice'],
                'serviceCharge' => $arrData['serviceCharge'],
                'discount' => $arrData['discount'],
                'total' => $arrData['total'],
                'tax' => $arrData['tax'],
                'grand_total' => $arrData['grandTotal'],
                'currency_text' => $arrData['currencyText'],
                'currency_text_position' => $arrData['currencyTextPosition'],
                'currency_symbol' => $arrData['currencySymbol'],
                'currency_symbol_position' => $arrData['currencySymbolPosition'],
                'payment_method' => $arrData['paymentMethod'],
                'gateway_type' => $arrData['gatewayType'],
                'payment_status' => $arrData['payment_status'],
                'order_status' => $orderStatus,
                'attachment' => $arrData['attachment']
            ]);

            // Trigger Notification
            try {
                $vendor = Vendor::find($arrData['vendor_id']);
                $roomContent = $room->room_content()->first();
                $notifData = [
                    'username' => $arrData['booking_name'],
                    'perahu_name' => $roomContent ? $roomContent->title : 'Perahu',
                    'booking_date' => $arrData['check_in_date'],
                    'order_id' => $orderNumber,
                    'recipient' => $arrData['booking_email'],
                    'vendor_phone' => $vendor->phone ?? null,
                ];
                $this->notificationService->send($booking, 'booking_placed', $notifData);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send booking_placed notification: ' . $e->getMessage());
            }

            return $booking;
        });
    }

    public function generateInvoice($bookingInfo)
    {
        $fileName = $bookingInfo->order_number . '.pdf';
        $directory = 'invoices/perahu/';
        
        Storage::makeDirectory($directory);
        
        $pdf = Pdf::loadView('frontend.pdf.room_booking', compact('bookingInfo'))->output();
        Storage::put($directory . $fileName, $pdf);

        return $fileName;
    }

    public function downloadInvoice($orderNumber)
    {
        $booking = Booking::where('order_number', $orderNumber)->firstOrFail();

        if (Auth::guard('web')->id() !== $booking->user_id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $filePath = 'invoices/perahu/' . $booking->invoice;

        if (!Storage::exists($filePath)) {
            abort(404, 'Invoice file not found.');
        }

        return Storage::download($filePath, "invoice-{$orderNumber}.pdf");
    }

    public function prepareMailForCustomer($bookingInfo)
    {
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'room_booking')->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        $info = Basic::select('website_title')->first();

        $customerName = $bookingInfo->booking_name;
        $orderNumber = $bookingInfo->order_number;
        $websiteTitle = $info->website_title;

        $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
        $mailBody = str_replace('{order_number}', $orderNumber, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

        $mailData['body'] = $mailBody;
        $mailData['recipient'] = $bookingInfo->booking_email;
        $mailData['invoice'] = Storage::path('invoices/perahu/') . $bookingInfo->invoice;

        BasicMailer::sendMail($mailData);

        if ($bookingInfo->payment_status == '1') {
            try {
                \Illuminate\Support\Facades\Mail::to($bookingInfo->booking_email)->send(new \App\Mail\PaymentReceivedMail($bookingInfo));
                
                $vendorEmail = null;
                if ($bookingInfo->vendor_id != 0) {
                    $vendor = \App\Models\Vendor::where('id', $bookingInfo->vendor_id)->first();
                    if ($vendor) {
                        $vendorEmail = $vendor->to_mail ?? $vendor->email;
                    }
                } else {
                    $info = \App\Models\BasicSettings\Basic::select('to_mail')->first();
                    if ($info) {
                        $vendorEmail = $info->to_mail;
                    }
                }
                
                if ($vendorEmail) {
                    \Illuminate\Support\Facades\Mail::to($vendorEmail)->send(new \App\Mail\PaymentReceivedMail($bookingInfo));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send PaymentReceivedMail: ' . $e->getMessage());
            }
        }
    }

    public function prepareMailForvendor($bookingInfo)
    {
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'inform_vendor_about_room_booking')->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        $info = Basic::select('website_title', 'to_mail')->first();

        if ($bookingInfo->vendor_id != 0) {
            $vendor = Vendor::where('id', $bookingInfo->vendor_id)->select('to_mail', 'email', 'username')->first();
            $mailData['recipient'] = $vendor->to_mail ?? $vendor->email;
            $vendorUserName = $vendor->username;
        } else {
            $mailData['recipient'] = $info->to_mail;
            $vendorUserName = 'Admin';
        }

        $mailBody = str_replace('{username}', $vendorUserName, $mailBody);
        $mailBody = str_replace('{customer_name}', $bookingInfo->booking_name, $mailBody);
        $mailBody = str_replace('{order_number}', $bookingInfo->order_number, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

        $mailData['body'] = $mailBody;
        $mailData['invoice'] = Storage::path('invoices/perahu/') . $bookingInfo->invoice;

        BasicMailer::sendMail($mailData);
    }

    public function complete($type = null)
    {
        $misc = new MiscellaneousController();
        $information['bgImg'] = $misc->getBreadcrumb();
        $information['purchaseType'] = $type;
        return view('frontend.perahu.booking-success', $information);
    }

    public function cancel(Request $request)
    {
        Session::flash('warning', 'Payment Cancel.');
        return redirect()->route('frontend.perahu');
    }
}
