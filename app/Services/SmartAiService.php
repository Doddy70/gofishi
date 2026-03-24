<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class SmartAiService
{
    /**
     * Generate an attractive boat description based on technical specs.
     */
    public function generateBoatDescription(array $specs)
    {
        $prompt = "Tuliskan deskripsi pemasaran yang menarik dan profesional dalam Bahasa Indonesia untuk penyewaan perahu dengan spesifikasi berikut:\n";
        $prompt .= "- Nama Perahu: {$specs['title']}\n";
        $prompt .= "- Ukuran: {$specs['length']}m x {$specs['width']}m\n";
        $prompt .= "- Kapasitas: {$specs['capacity']} orang\n";
        $prompt .= "- Mesin: {$specs['engine']}\n";
        $prompt .= "- Fasilitas Utama: " . (is_array($specs['amenities']) ? implode(', ', $specs['amenities']) : $specs['amenities']) . "\n";
        $prompt .= "\nBuatlah deskripsi bergaya Airbnb yang menonjolkan kenyamanan dan pengalaman memancing yang tak terlupakan.";

        return $this->askAI($prompt);
    }

    /**
     * Generate a professional host reply for a review.
     */
    public function generateReviewReply(string $customerName, string $reviewText)
    {
        $prompt = "Sebagai host penyewaan perahu bernama Gofishi, tuliskan balasan terima kasih yang sopan dan ramah dalam Bahasa Indonesia untuk ulasan pelanggan berikut:\n";
        $prompt .= "Nama Pelanggan: {$customerName}\n";
        $prompt .= "Ulasan: \"{$reviewText}\"\n";
        $prompt .= "\nBalasan harus profesional, menunjukkan apresiasi, dan mengajak mereka kembali lagi.";

        return $this->askAI($prompt);
    }

    /**
     * Generate structured search parameters from natural language query.
     */
    public function generateSmartSearchQuery(string $naturalQuery)
    {
        $settings = \App\Models\BasicSettings\Basic::select('google_gemini_prompt')->first();
        $systemPrompt = $settings->google_gemini_prompt ?? "Anda adalah asisten cerdas GoFishi, sistem pencarian perahu marketplace sewa kapal pancing. Tugas Anda mengekstrak informasi dari kalimat user ke dalam JSON murni dengan key: `location` (string/null), `adults` (integer/null), `keyword` (string/null). Berikan HANYA format valid JSON.";

        $prompt = "{$systemPrompt}\n\nUser mengirimkan kalimat pencarian: \"{$naturalQuery}\"\nContoh valid: {\"location\": \"Bali\", \"adults\": 5, \"keyword\": \"AC\"}";

        $response = $this->askGemini($prompt);
        
        // Membersihkan markdown bila AI tetap bandel
        $cleanedResponse = str_replace(['```json', '```'], '', $response);
        return json_decode(trim($cleanedResponse), true);
    }

    /**
     * Generate a conversational reply for the AI Chat Bubble.
     */
    public function generateChatReply(string $userMessage)
    {
        $settings = \App\Models\BasicSettings\Basic::select('google_gemini_prompt')->first();
        $systemPrompt = $settings->google_gemini_prompt ?? "Anda adalah asisten cerdas GoFishi, sistem pencarian perahu marketplace sewa kapal pancing. Jawablah pertanyaan user dengan ramah dan informatif.";

        $prompt = "System: {$systemPrompt}\n\nUser: \"{$userMessage}\"\nAssistant:";

        return $this->askAI($prompt);
    }

    /**
     * Main Orchestrator to ask AI based on provider.
     */
    protected function askAI(string $prompt)
    {
        $provider = env('AI_PROVIDER', 'openai');

        try {
            if ($provider == 'gemini') {
                return $this->askGemini($prompt);
            } else {
                return $this->askOpenAI($prompt);
            }
        } catch (\Exception $e) {
            Log::error("AI Error ({$provider}): " . $e->getMessage());
            return "Maaf, asisten AI ({$provider}) sedang tidak tersedia saat ini. Silakan coba lagi nanti.";
        }
    }

    /**
     * Call OpenAI API.
     */
    protected function askOpenAI(string $prompt)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Anda adalah asisten cerdas untuk platform Gofishi, marketplace sewa perahu pancing.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $result->choices[0]->message->content;
    }

    /**
     * Call Google Gemini API.
     */
    protected function askGemini(string $prompt)
    {
        // Mengambil API Key dari BasicSettings (database) atau fallback .env
        $basicSettings = \App\Models\BasicSettings\Basic::select('google_gemini_status', 'google_gemini_api_key')->first();
        if ($basicSettings && $basicSettings->google_gemini_status == 1 && $basicSettings->google_gemini_api_key) {
            config(['gemini.api_key' => $basicSettings->google_gemini_api_key]);
        }

        $result = app('gemini')->generativeModel('gemini-2.5-flash')->generateContent($prompt);
        return $result->text();
    }
}
