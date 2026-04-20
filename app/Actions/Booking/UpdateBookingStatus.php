<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

/**
 * Action: Mengupdate status pesanan (Approve/Reject) dan kirim notifikasi (Standar 2026).
 */
class UpdateBookingStatus
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Jalankan update status.
     * 
     * @param Booking $booking
     * @param string $status ('approved' atau 'rejected')
     * @param string|null $reason Alasan penolakan
     */
    public function execute(Booking $booking, string $status, ?string $reason = null): bool
    {
        try {
            $oldStatus = $booking->order_status;
            
            // 1. Update status
            $booking->update([
                'order_status' => $status,
                'rejection_reason' => ($status === 'rejected') ? $reason : null
            ]);

            // 2. Persiapkan data notifikasi
            $roomInfo = $booking->hotelRoom()->first();
            $roomContent = $roomInfo ? $roomInfo->room_content()->first() : null;
            
            $notifData = [
                'username' => $booking->booking_name,
                'perahu_name' => $roomContent ? $roomContent->title : 'Perahu',
                'booking_date' => $booking->check_in_date,
                'order_id' => $booking->order_number,
                'recipient' => $booking->booking_email,
                'reason' => $reason
            ];

            // 3. Kirim Notifikasi via Unified Service
            if ($status === 'approved' && $oldStatus !== 'approved') {
                $this->notificationService->send($booking, 'booking_accepted', $notifData);
            } elseif ($status === 'rejected' && $oldStatus !== 'rejected') {
                $this->notificationService->send($booking, 'booking_rejected', $notifData);
            }

            Log::info("[UpdateBookingStatus] Status Booking #{$booking->order_number} diubah ke {$status}.");
            return true;
        } catch (\Exception $e) {
            Log::error("[UpdateBookingStatus] Gagal update status: " . $e->getMessage());
            return false;
        }
    }
}
