<?php

namespace App\Services;

use App\Models\BasicSettings\Basic;
use App\Contracts\Messaging\WhatsAppProvider;
use App\Http\Helpers\MegaMailer;
use Illuminate\Support\Facades\Log;

/**
 * High-Scale Notification Service (Standard 2026 Architect)
 * Using Hexagonal Architecture with WhatsApp Port injection.
 */
class NotificationService
{
    /**
     * @param WhatsAppProvider $waProvider Port implementation injected via Service Container.
     */
    public function __construct(
        protected WhatsAppProvider $waProvider
    ) {}

    /**
     * Send notification via both Email and WhatsApp.
     */
    public function send($user, $type, $data)
    {
        // 1. Send Email (Existing Logic)
        $this->sendEmail($user, $type, $data);

        // 2. Send WhatsApp
        $this->sendWhatsApp($user, $type, $data);
    }

    /**
     * Handle Email Notification
     */
    protected function sendEmail($user, $type, $data)
    {
        try {
            $mailer = new MegaMailer();
            $data['templateType'] = $type;
            $data['toMail'] = $user->email ?? ($data['recipient'] ?? null);
            
            if ($data['toMail']) {
                $mailer->mailFromAdmin($data);
            }
        } catch (\Exception $e) {
            Log::error("[Notification] Email failed: " . $e->getMessage());
        }
    }

    /**
     * Handle WhatsApp Notification (Hexagonal Dispatch)
     */
    public function sendWhatsApp($user, $type, $data)
    {
        $bs = Basic::select('whatsapp_status')->first();
        if (!$bs || $bs->whatsapp_status != 1) return;

        // Target: User/Customer
        $phone = $user->phone ?? $user->whatsapp_number ?? ($data['booking_phone'] ?? null);
        if ($phone) {
            $message = $this->prepareWAMessage($type, $data);
            $this->waProvider->sendMessage($phone, $message);
        }

        // Target: Host/Vendor (Optional)
        if (!empty($data['vendor_phone'])) {
            $hostMessage = $this->prepareHostWAMessage($type, $data);
            $this->waProvider->sendMessage($data['vendor_phone'], $hostMessage);
        }
    }

    /**
     * Message Template for Host
     */
    protected function prepareHostWAMessage($type, $data): string
    {
        $websiteTitle = Basic::first()->website_title ?? 'Gofishi';
        return match ($type) {
            'booking_placed'   => "Halo Host! Pesanan baru masuk untuk perahu Anda: {$data['perahu_name']}. Segera cek dashboard Anda. - $websiteTitle",
            'payment_received' => "Host, pembayaran booking #{$data['order_id']} telah diterima. Jadwal terkonfirmasi! - $websiteTitle",
            default            => "Notifikasi Host Baru - $websiteTitle",
        };
    }

    /**
     * Message Template for Customer
     */
    protected function prepareWAMessage($type, $data): string
    {
        $websiteTitle = Basic::first()->website_title ?? 'Gofishi';
        
        return match ($type) {
            'booking_placed'   => "Halo {$data['username']}, booking Anda untuk {$data['perahu_name']} telah diterima. Silakan selesaikan pembayaran untuk mengamankan jadwal. Terima kasih - $websiteTitle",
            'booking_accepted' => "Kabar gembira! Booking Anda untuk {$data['perahu_name']} pada tanggal {$data['booking_date']} telah DISETUJUI oleh Host. Selamat memancing! - $websiteTitle",
            'booking_rejected' => "Mohon maaf, booking Anda untuk {$data['perahu_name']} telah DITOLAK. Silakan cek dashboard untuk detail alasan atau pilih jadwal lain. - $websiteTitle",
            'payment_received' => "Pembayaran Anda untuk booking #{$data['order_id']} telah kami terima. Jadwal Anda sudah terkonfirmasi secara resmi. Sampai jumpa di dermaga! - $websiteTitle",
            default            => "Notifikasi baru dari $websiteTitle untuk aktivitas Anda.",
        };
    }
}
