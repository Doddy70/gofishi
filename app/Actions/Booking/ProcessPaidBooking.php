<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\Vendor;
use App\Models\BasicSettings\Basic;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Action: Menangani proses setelah pemesanan dibayar (Standar 2026).
 */
class ProcessPaidBooking
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Jalankan proses update status, saldo, dan kirim notifikasi.
     */
    public function execute(Booking $booking, string $transactionId): bool
    {
        return DB::transaction(function () use ($booking, $transactionId) {
            // Cek jika sudah lunas
            if ($booking->payment_status == 1) {
                Log::info("[ProcessPaidBooking] Booking #{$booking->order_number} sudah lunas sebelumnya.");
                return true;
            }

            // 1. Update Booking Status
            $booking->update([
                'payment_status' => 1, // Completed
                'order_status' => 'approved',
            ]);

            // 2. Transaksi & Saldo Vendor
            $this->handleVendorBalance($booking, $transactionId);

            // 3. Notifikasi (Email & WhatsApp via unified Service)
            $this->sendNotifications($booking);

            Log::info("[ProcessPaidBooking] Berhasil memproses pembayaran untuk Booking #{$booking->order_number}.");
            return true;
        });
    }

    protected function handleVendorBalance(Booking $booking, string $transactionId)
    {
        $vendor_id = $booking->vendor_id;
        $grandTotal = $booking->grand_total;

        // Hitung Komisi (Contoh sederhana: 0 jika admin, logic asli bisa dikembangkan)
        $commission = ($vendor_id == 0) ? $grandTotal : 0;

        // Update Global Earning
        $earning = Basic::first();
        if ($earning) {
            $earning->total_earning += ($vendor_id == 0) ? $grandTotal : $commission;
            $earning->save();
        }

        // Update Saldo Vendor
        $pre_balance = null;
        $after_balance = null;
        if ($vendor_id != 0) {
            $vendor = Vendor::find($vendor_id);
            if ($vendor) {
                $pre_balance = $vendor->amount;
                $vendor->amount += ($grandTotal - $commission);
                $vendor->save();
                $after_balance = $vendor->amount;
            }
        }

        // Simpan Log Transaksi (Memanggil helper store_transaction yang ada)
        if (function_exists('store_transaction')) {
            store_transaction([
                'transcation_id' => $transactionId,
                'booking_id' => $booking->id,
                'transcation_type' => 'room_booking',
                'user_id' => $booking->user_id,
                'vendor_id' => $vendor_id,
                'payment_status' => 1,
                'payment_method' => $booking->payment_method,
                'grand_total' => $grandTotal,
                'commission' => $commission,
                'pre_balance' => $pre_balance,
                'after_balance' => $after_balance,
                'gateway_type' => $booking->gateway_type,
                'currency_symbol' => $booking->currency_symbol,
                'currency_symbol_position' => $booking->currency_symbol_position,
            ]);
        }
    }

    protected function sendNotifications(Booking $booking)
    {
        $roomContent = $booking->hotelRoom->room_content()->first();
        $vendor = $booking->vendor()->first();

        $data = [
            'username' => $booking->booking_name,
            'perahu_name' => $roomContent ? $roomContent->title : 'Perahu',
            'booking_date' => $booking->check_in_date,
            'order_id' => $booking->order_number,
            'recipient' => $booking->booking_email,
            'booking_phone' => $booking->booking_phone,
            'vendor_phone' => $vendor ? $vendor->phone : null,
        ];

        $this->notificationService->send($booking, 'payment_received', $data);
    }
}
