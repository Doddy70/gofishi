<?php

namespace App\Contracts\Messaging;

/**
 * Port: WhatsApp Provider Interface (Standard 2026 Hexagonal)
 */
interface WhatsAppProvider
{
    /**
     * Send a single WhatsApp message.
     *
     * @param string $phone Target phone number (E.164 format or 0-prefix)
     * @param string $message The message body
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool;
}
