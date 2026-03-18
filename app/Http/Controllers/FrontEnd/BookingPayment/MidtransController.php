<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Actions\Booking\ProcessPaidBooking;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Booking;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

/**
 * Controller: Menangani Pembayaran Midtrans (Standar 2026 Architect).
 * Disederhanakan dengan mendelegasikan logika bisnis ke ProcessPaidBooking Action.
 */
class MidtransController extends Controller
{
    public function __construct(
        protected ProcessPaidBooking $processPaidBooking
    ) {}

    public function index(Request $request, $paymentFor, $userType)
    {
        if (!$request->session()->has('price')) {
            Session::flash('error', 'Sesi pemesanan telah berakhir. Silakan coba lagi.');
            return redirect()->back();
        }

        $priceId = $request->session()->get('price');
        $bookingProcess = new BookingController();

        // 1. Hitung ulang total biaya
        $calculatedData = $bookingProcess->calculation($request, $priceId);
        $currencyInfo = $this->getCurrencyInfo();

        if ($currencyInfo->base_currency_text !== 'IDR') {
            return redirect()->back()->with('error', 'Midtrans hanya mendukung mata uang IDR.')->withInput();
        }

        // 2. Ambil Konfigurasi Midtrans dari DB
        $gateway = OnlineGateway::whereKeyword('midtrans')->first();
        $info = json_decode($gateway->information, true);

        $isProd = false;
        if (array_key_exists('midtrans_mode', $info)) {
            $isProd = ($info['midtrans_mode'] == 0);
        } elseif (array_key_exists('sandbox_status', $info)) {
            $isProd = ($info['sandbox_status'] == 0);
        } elseif (array_key_exists('is_production', $info)) {
            $isProd = ($info['is_production'] == 1);
        }
        
        MidtransConfig::$serverKey = $info['server_key'] ?? '';
        MidtransConfig::$isProduction = $isProd;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        // 3. Persiapkan data booking (termasuk verifikasi usia 17+)
        $arrData = $bookingProcess->timeCheck($request, 'Midtrans');
        
        // Simpan age_confirmation ke dalam arrData
        $arrData['age_confirmation'] = $request->has('age_confirmation') ? 1 : 0;

        // 4. Generate Order ID and Store Booking
        $orderId = \Carbon\Carbon::now()->format('ymdHis') . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));
        $arrData['order_number'] = $orderId;
        
        // Anti-clash: Hapus booking pending (online) milik user ini untuk armada/jadwal yang sama sebelum membuat yang baru.
        // Ini mencegah user terblokir oleh 'dirinya sendiri' saat mencoba ulang/retry checkout.
        \App\Models\Booking::where('room_id', $arrData['room_id'])
            ->where('booking_email', $request['booking_email'])
            ->where('check_in_date', $arrData['check_in_date'])
            ->where('payment_status', 0)
            ->where('gateway_type', 'online')
            ->where('order_status', 'pending')
            ->delete();

        $bookingInfo = $bookingProcess->storeData($arrData);
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => intval($calculatedData['grandTotal']), 
            ],
            'customer_details' => [
                'email' => $request['booking_email'],
                'phone' => $request['booking_phone'],
                'first_name' => $request['booking_name'],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("[Midtrans] Snap Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal terhubung ke Midtrans.');
        }

        // 5. Simpan state sementara ke session
        session()->put('paymentFor', $paymentFor);
        session()->put('arrData', $arrData);
        session()->put('midtrans_order_id', $orderId);

        $data = $info; // Match view variable name
        $data['client_key'] = $info['client_key'] ?? ''; // Ensure client_key is available for blade

        return view('frontend.payment.booking-midtrans', compact('snapToken', 'data'));
    }

    /**
     * Redirect: Dipanggil setelah user dialihkan kembali dari Snap.
     */
    public function creditCardNotify(Request $request)
    {
        $arrData = $request->session()->get('arrData');
        $midtransOrderId = $request->session()->get('midtrans_order_id');
        
        if (!$arrData) {
            return redirect()->route('index')->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $bookingProcess = new BookingController();

        $bookingInfo = Booking::where('order_number', $midtransOrderId)->first();

        if ($bookingInfo && $bookingInfo->payment_status != 1) {
            // 2. Generate Invoice
            $invoice = $bookingProcess->generateInvoice($bookingInfo);
            $bookingInfo->update(['invoice' => $invoice]);
    
            // 3. Jalankan Action ProcessPaidBooking
            $this->processPaidBooking->execute($bookingInfo, $midtransOrderId);
        }

        // 4. Bersihkan Sesi
        $this->clearBookingSession($request);

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'online']);
    }

    /**
     * Webhook: Notifikasi Asinkron dari Midtrans (Sangat Penting).
     */
    public function webhook(Request $request)
    {
        Log::info("[Midtrans Webhook] Menerima notifikasi.");

        $gateway = OnlineGateway::whereKeyword('midtrans')->first();
        $info = json_decode($gateway->information, true);

        $isProd = false;
        if (array_key_exists('midtrans_mode', $info)) {
            $isProd = ($info['midtrans_mode'] == 0);
        } elseif (array_key_exists('sandbox_status', $info)) {
            $isProd = ($info['sandbox_status'] == 0);
        } elseif (array_key_exists('is_production', $info)) {
            $isProd = ($info['is_production'] == 1);
        }
        
        MidtransConfig::$serverKey = $info['server_key'] ?? '';
        MidtransConfig::$isProduction = $isProd;

        try {
            $notif = new MidtransNotification();
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $type = $notif->payment_type;

            // Cari booking berdasarkan order_number atau simpan mapping midtrans_id
            // Untuk GoFishi, kita mungkin perlu menyimpan order_id midtrans di tabel bookings
            // atau menggunakan order_number yang sinkron.
            
            // Mencari booking yang belum lunas
            $booking = Booking::where('payment_status', '!=', 1)
                              ->where('order_number', $orderId) // Asumsi orderId Midtrans == order_number
                              ->first();

            if (!$booking) {
                 Log::warning("[Midtrans Webhook] Booking tidak ditemukan atau sudah diproses: {$orderId}");
                 return response()->json(['status' => 'ignored']);
            }

            if ($transaction == 'settlement' || $transaction == 'capture') {
                $this->processPaidBooking->execute($booking, $notif->transaction_id);
                Log::info("[Midtrans Webhook] Pembayaran sukses: {$orderId}");
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("[Midtrans Webhook] Error: " . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function clearBookingSession(Request $request)
    {
        $request->session()->forget([
            'price', 'checkInTime', 'checkInDate', 'adult', 'children',
            'roomDiscount', 'takeService', 'serviceCharge', 'arrData', 'midtrans_order_id'
        ]);
    }
}
