<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\Perahu;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controller: Menangani Pembayaran Offline / Manual (Standar 2026 Architect).
 */
class OfflineController extends Controller
{
    public function index(Request $request)
    {
        $gatewayId = $request->gateway;
        $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);

        // 1. Validasi Lampiran Bukti Transfer
        if ($offlineGateway->has_attachment == 1) {
            $rules = [
                'attachment' => ['required', new ImageMimeTypeRule()]
            ];
            $validator = Validator::make($request->only('attachment'), $rules);
            Session::flash('gatewayId', $offlineGateway->id);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }

        if (!$request->session()->has('price')) {
            Session::flash('error', 'Sesi pemesanan telah berakhir.');
            return redirect()->back();
        }

        $bookingProcess = new BookingController();

        // 2. Persiapkan Data (Gunakan helper timeCheck yang sudah ada di BookingController)
        $arrData = $bookingProcess->timeCheck($request, $offlineGateway->name);
        $arrData['gatewayType'] = 'offline';
        $arrData['payment_status'] = 0; // Pending untuk offline
        $arrData['age_confirmation'] = $request->has('age_confirmation') ? 1 : 0;

        // 3. Handle Upload Bukti Transfer
        if ($request->hasFile('attachment')) {
            $directory = public_path('assets/file/attachments/room-booking/');
            $arrData['attachment'] = UploadFile::store($directory, $request->file('attachment'));
        }

        // 4. Simpan ke Database
        $bookingInfo = $bookingProcess->storeData($arrData);

        // 5. Generate Invoice
        $invoice = $bookingProcess->generateInvoice($bookingInfo);
        $bookingInfo->update(['invoice' => $invoice]);

        // 6. Notifikasi via Unified Service (untuk status booking baru)
        // Catatan: ProcessPaidBooking tidak dipanggil karena belum lunas.
        $bookingProcess->prepareMailForvendor($bookingInfo);

        // 7. Bersihkan Sesi
        $this->clearBookingSession($request);

        return redirect()->route('frontend.perahu.booking.complete', ['type' => 'offline_booking']);
    }

    protected function clearBookingSession(Request $request)
    {
        $request->session()->forget([
            'price', 'checkInTime', 'checkInDate', 'adult', 'children',
            'roomDiscount', 'takeService', 'serviceCharge', 'room_id', 
            'day_package', 'room_price', 'checkOutDate', 'checkOutTime'
        ]);
    }
}
