<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Actions\Booking\ProcessPaidBooking;
use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Models\BasicSettings\Basic;
use App\Models\Vendor;

/**
 * Controller: Menangani Pembayaran PayPal (Standar 2026 Architect).
 */
class PayPalController extends Controller
{
    private $api_context;

    public function __construct(
        protected ProcessPaidBooking $processPaidBooking
    ) {
        $gateway = OnlineGateway::whereKeyword('paypal')->first();
        if (!$gateway) {
            $this->api_context = null;
            return;
        }

        $paypalData = json_decode($gateway->information, true) ?? [];

        $paypal_conf = Config::get('paypal', []);
        $paypal_conf['client_id'] = $paypalData['client_id'] ?? ($paypal_conf['client_id'] ?? null);
        $paypal_conf['secret'] = $paypalData['client_secret'] ?? ($paypal_conf['secret'] ?? null);
        $paypal_conf['settings']['mode'] = ($paypalData['sandbox_status'] ?? 0) == 1 ? 'sandbox' : 'live';

        // Initialize PayPal API context only when SDK classes exist.
        if (
            class_exists(ApiContext::class) &&
            class_exists(OAuthTokenCredential::class) &&
            !empty($paypal_conf['client_id']) &&
            !empty($paypal_conf['secret'])
        ) {
            $this->api_context = new ApiContext(
                new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret'])
            );
            $this->api_context->setConfig($paypal_conf['settings']);
        } else {
            $this->api_context = null;
        }

        // If the SDK isn't installed or config is incomplete, $this->api_context remains null.
    }

    public function index(Request $request, $paymentFor)
    {
        if (!$request->session()->has('price')) {
            Session::flash('error', 'Sesi pemesanan telah berakhir.');
            return redirect()->back();
        }

        $priceId = $request->session()->get('price');
        $bookingProcess = new BookingController();

        // 1. Persiapkan data booking (termasuk verifikasi usia 17+)
        $arrData = $bookingProcess->timeCheck($request, 'Paypal');
        $arrData['age_confirmation'] = $request->has('age_confirmation') ? 1 : 0;

        // 2. Kalkulasi biaya
        $calculatedData = $bookingProcess->calculation($request, $priceId);
        $currencyInfo = $this->getCurrencyInfo();

        // PayPal default ke USD jika mata uang dasar bukan USD
        $paypalTotal = $calculatedData['grandTotal'];
        if ($currencyInfo->base_currency_text !== 'USD') {
            $rate = floatval($currencyInfo->base_currency_rate);
            $paypalTotal = round($calculatedData['grandTotal'] / $rate, 2);
        }

        $title = 'Perahu Booking';
        $notifyURL = route('frontend.perahu.booking.paypal.notify');
        $cancelURL = route('frontend.perahu');

        if (!$this->api_context) {
            return redirect($cancelURL)->with('error', 'PayPal integration is not available (SDK/config missing).');
        }

        // 3. Konfigurasi PayPal Payment
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($title)
               ->setCurrency('USD')
               ->setQuantity(1)
               ->setPrice($paypalTotal);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
               ->setTotal($paypalTotal);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription($title . ' via PayPal');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($notifyURL)
                      ->setCancelUrl($cancelURL);

        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

        try {
            $payment->create($this->api_context);
        } catch (\Exception $ex) {
            return redirect($cancelURL)->with('error', 'PayPal Connection Error: ' . $ex->getMessage());
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirectURL = $link->getHref();
                break;
            }
        }

        // 4. Simpan state sementara
        $request->session()->put('paymentId', $payment->getId());
        $request->session()->put('arrData', $arrData);

        if (isset($redirectURL)) {
            return Redirect::away($redirectURL);
        }

        return redirect($cancelURL)->with('error', 'Gagal mendapatkan URL Pembayaran PayPal.');
    }

    public function notify(Request $request)
    {
        if (!$this->api_context) {
            return redirect()->route('frontend.perahu')->with('error', 'PayPal integration is not available (SDK/config missing).');
        }

        $paymentId = $request->session()->get('paymentId');
        $arrData = $request->session()->get('arrData');
        $urlInfo = $request->all();

        if (empty($urlInfo['PayerID']) || empty($urlInfo['token'])) {
            return redirect()->route('frontend.perahu')->with('error', 'Pembayaran PayPal dibatalkan.');
        }

        /** Execute The Payment **/
        $payment = Payment::get($paymentId, $this->api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($urlInfo['PayerID']);

        try {
            $result = $payment->execute($execution, $this->api_context);
            if ($result->getState() == 'approved') {
                $bookingProcess = new BookingController();

                // 1. Simpan booking (Pending)
                $bookingInfo = $bookingProcess->storeData($arrData);

                // 2. Generate Invoice
                $invoice = $bookingProcess->generateInvoice($bookingInfo);
                $bookingInfo->update(['invoice' => $invoice]);

                // 3. Proses Pelunasan via Action terpusat
                $this->processPaidBooking->execute($bookingInfo, $paymentId);

                // 4. Bersihkan Sesi
                $this->clearBookingSession($request);

                return redirect()->route('frontend.perahu.booking.complete', ['type' => 'online']);
            }
        } catch (\Exception $e) {
            return redirect()->route('frontend.perahu')->with('error', 'Eksekusi PayPal Gagal: ' . $e->getMessage());
        }

        return redirect()->route('frontend.perahu')->with('error', 'Status pembayaran tidak valid.');
    }

    protected function clearBookingSession(Request $request)
    {
        $request->session()->forget([
            'price', 'checkInTime', 'checkInDate', 'adult', 'children',
            'roomDiscount', 'takeService', 'serviceCharge', 'arrData', 'paymentId'
        ]);
    }
}
