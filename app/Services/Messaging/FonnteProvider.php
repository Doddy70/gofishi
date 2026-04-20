<?php

namespace App\Services\Messaging;

use App\Contracts\Messaging\WhatsAppProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Adapter: Fonnte WhatsApp Provider Implementation (Standard 2026)
 */
class FonnteProvider implements WhatsAppProvider
{
    protected string $token;
    protected string $endpoint = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.whatsapp.token') ?? '';
    }

    /**
     * Send a WhatsApp message via Fonnte.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // International format normalization (Standard 2026 pattern)
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        Log::info("[Fonnte] Attempting to send WhatsApp to {$phone}.");

        if (empty($this->token)) {
            Log::warning("[Fonnte] WhatsApp API Token is missing. Message skipped.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->endpoint, [
                'target' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("[Fonnte] Message sent successfully to {$phone}.");
                return true;
            }

            Log::error("[Fonnte] API returned error: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("[Fonnte] Connection failed: " . $e->getMessage());
            return false;
        }
    }
}
