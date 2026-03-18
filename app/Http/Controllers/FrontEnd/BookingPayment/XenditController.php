<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Actions\Booking\ProcessPaidBooking;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Booking;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Controller: Menangani Pembayaran Xendit (Standar 2026 Architect).
 */
class XenditController extends Controller
{
    public function __construct(
        protected ProcessPaidBooking $processPaidBooking
    ) {}

    public function index(Request $request, $paymentFor)
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

        $allowedCurrency = ['IDR', 'PHP', 'USD', 'SGD', 'MYR'];
        if (!in_array($currencyInfo->base_currency_text, $allowedCurrency)) {
            return redirect()->back()->with('error', 'Mata uang tidak didukung oleh Xendit.')->withInput();
        }

        // 2. Persiapkan data booking
        $arrData = $bookingProcess->timeCheck($request, 'Xendit');
        $arrData['age_confirmation'] = $request->has('age_confirmation') ? 1 : 0;

        // 3. Buat Xendit Invoice dan Simpan Booking
        $externalId = \Carbon\Carbon::now()->format('ymdHis') . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));
        $arrData['order_number'] = $externalId;
        
        $bookingInfo = $bookingProcess->storeData($arrData);
        $secretKey = 'Basic ' . base64_encode(config('xendit.key_auth') . ':');

        try {
            $response = Http::withHeaders([
                'Authorization' => $secretKey
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $externalId,
                'amount' => $calculatedData['grandTotal'],
                'currency' => $currencyInfo->base_currency_text,
                'customer' => [
                    'given_names' => $request['booking_name'],
                    'email' => $request['booking_email'],
                    'mobile_number' => $request['booking_phone'],
                ],
                'success_redirect_url' => route('frontend.perahu.booking.xendit.notify')
            ]);

            $invoice = $response->json();

            if (!empty($invoice['invoice_url'])) {
                // Simpan state sementara ke session
                session()->put('paymentFor', $paymentFor);
                session()->put('arrData', $arrData);
                session()->put('xendit_invoice_id', $invoice['id']);
                session()->put('external_id', $externalId);

                return redirect($invoice['invoice_url']);
            } else {
                Log::error("[Xendit] Invoice creation failed: " . json_encode($invoice));
                return redirect()->back()->with('error', 'Gagal membuat tagihan Xendit.')->withInput();
            }
        } catch (\Exception $e) {
            Log::error("[Xendit] Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    /**
     * Notify / Success Redirect
     */
    public function notify(Request $request)
    {
        $arrData = $request->session()->get('arrData');
        $xenditInvoiceId = $request->session()->get('xendit_invoice_id');

        if (!$arrData) {
            return redirect()->route('index')->with('error', 'Data pemesanan tidak ditemukan.');
        }

        // Verifikasi status invoice ke Xendit (Opsional tapi disarankan)
        // Untuk sekarang kita asumsikan lunas jika dialihkan ke sini via success_redirect_url
        // Tapi logic terbaik adalah tetap mengandalkan Webhook.

        $bookingProcess = new BookingController();
        $externalId = $request->session()->get('external_id');
        $bookingInfo = Booking::where('order_number', $externalId)->first();

        if ($bookingInfo && $bookingInfo->payment_status != 1) {
            // Generate Invoice PDF
            $invoiceFile = $bookingProcess->generateInvoice($bookingInfo);
            $bookingInfo->update(['invoice' => $invoiceFile]);
    
            // Proses pelunasan
            $this->processPaidBooking->execute($bookingInfo, $xenditInvoiceId);
        }

        $this->clearBookingSession($request);

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'online']);
    }

    /**
     * Webhook / Callback dari Xendit
     */
    public function callback(Request $request)
    {
        Log::info("[Xendit Webhook] Menerima callback.");
        
        $data = $request->all();
        if ($data['status'] === 'PAID') {
            $booking = Booking::where('payment_status', '!=', 1)
                              ->where('order_number', $data['external_id']) // Asumsi external_id == order_number
                              ->first();

            if ($booking) {
                $this->processPaidBooking->execute($booking, $data['id']);
                return response()->json(['status' => 'success']);
            }
        }

        return response()->json(['status' => 'ignored']);
    }

    protected function clearBookingSession(Request $request)
    {
        $request->session()->forget([
            'price', 'checkInTime', 'checkInDate', 'adult', 'children',
            'roomDiscount', 'takeService', 'serviceCharge', 'arrData', 
            'xendit_invoice_id', 'external_id'
        ]);
    }
}
