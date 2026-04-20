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
        $systemPrompt = $settings?->google_gemini_prompt ?? "Anda adalah asisten cerdas GoFishi, sistem pencarian perahu marketplace sewa kapal pancing. Tugas Anda mengekstrak informasi dari kalimat user ke dalam JSON murni dengan key: `location` (string/null), `adults` (integer/null), `keyword` (string/null). Berikan HANYA format valid JSON.";

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
        $systemPrompt = $settings?->google_gemini_prompt ?? "Anda adalah asisten cerdas GoFishi, sistem pencarian perahu marketplace sewa kapal pancing. Jawablah pertanyaan user dengan ramah dan informatif.";

        $prompt = "System: {$systemPrompt}\n\nUser: \"{$userMessage}\"\nAssistant:";

        return $this->askAI($prompt);
    }

    /**
     * Analyze sentiment and business insights from multiple raw guest reviews.
     */
    public function analyzeReviewsSentiment(array $reviewsText)
    {
        if (empty($reviewsText)) {
            return json_encode(['error' => 'Tidak ada ulasan untuk dianalisis.']);
        }

        $allReviews = implode("\n- ", $reviewsText);
        $settings = \App\Models\BasicSettings\Basic::select('google_gemini_prompt')->first();
        $systemPrompt = $settings?->google_gemini_prompt ?? "Anda adalah AI Business Analyst khusus untuk marketplace penyewaan perahu Gofishi.";

        $prompt = "{$systemPrompt}\n\nTugas: Analisis Kumpulan Ulasan Tamu berikut dan kembalikan wawasan bisnis yang tajam.\n";
        $prompt .= "Kumpulan Ulasan:\n- {$allReviews}\n\n";
        $prompt .= "Anda WAJIB merespons HANYA dalam format JSON baku persis seperti struktur ini (Dilarang memberikan teks markdown lain di luar JSON!):\n";
        $prompt .= "{\n";
        $prompt .= '  "sentiment_score": {"positive": 80, "neutral": 10, "negative": 10},'."\n";
        $prompt .= '  "top_compliments": ["Sering dipuji atas kebersihan", "Kapten sangat ramah"],'."\n";
        $prompt .= '  "top_complaints": ["Toilet terkadang kurang air", "Bau rokok di kabin"],'."\n";
        $prompt .= '  "business_advice": "Tingkatkan fasilitas toilet dan berikan penyegar udara."'."\n";
        $prompt .= "}";

        $response = $this->askGemini($prompt);
        
        $cleanedResponse = str_replace(['```json', '```', '``` JSON', '``` json'], '', $response);
        return trim($cleanedResponse);
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
        } catch (\Throwable $e) {
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
        $apiKey = env('GEMINI_API_KEY');
        $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";
        
        // Ambil System Prompt dari database untuk knowledge base
        $settings = \App\Models\BasicSettings\Basic::select('google_gemini_prompt')->first();
        $defaultPrompt = "Anda adalah GoFishi Smart Assistant. Visi kami adalah Menjadikan Wisata Air se-Indonesia dalam satu aplikasi web.
Misi Kami: Membantu pemilik setiap jenis perahu (kayak, perahu layar, perahu motor) untuk memberdayakan aset mereka yang kurang dimanfaatkan (rata-rata hanya digunakan 8% setahun). 
Tugas Anda: Mempromosikan perahu mitra untuk disewakan sebagai alat transportasi eksplorasi perairan Indonesia atau trip memancing. Bantu nelayan tanpa perahu mendapatkan akses sewa terbaik. 
Nada Bicara: Inspiratif, memberdayakan, dan teknis tentang perairan.";
        
        $systemInstruction = $settings?->google_gemini_prompt ?? $defaultPrompt;
        
        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => "Identitas & Aturan: " . $systemInstruction . "\n\nUser Message: " . $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($baseUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-goog-api-key: ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new \Exception("cURL Error: " . $err);
        }

        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        Log::error("Gemini Raw Response: " . $response);
        throw new \Exception("Format respon AI tidak dikenal atau API Key bermasalah.");
    }
}
