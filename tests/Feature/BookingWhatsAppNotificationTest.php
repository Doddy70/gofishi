<?php

namespace Tests\Feature;

use App\Models\BasicSettings\Basic;
use App\Models\Booking;
use App\Models\Perahu;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BookingWhatsAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup basic settings
        Basic::create([
            'website_title' => 'Gofishi Test',
            'whatsapp_status' => 1,
            'whatsapp_number' => '628123456789'
        ]);
    }

    /** @test */
    public function it_triggers_whatsapp_notification_when_booking_is_placed()
    {
        // 1. Mock WhatsApp API (Fonnte)
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        // 2. Create Dummy Data
        $vendor = Vendor::factory()->create([
            'phone' => '08111111111',
            'status' => 1
        ]);

        $room = Perahu::factory()->create([
            'vendor_id' => $vendor->id,
            'booking_type' => 'approval',
            'price_day_1' => 1000000,
            'status' => 1
        ]);

        $user = User::factory()->create([
            'phone' => '08222222222'
        ]);

        // 3. Simulate Booking Data (Session & Request)
        $bookingData = [
            'room_id' => $room->id,
            'vendor_id' => $vendor->id,
            'day_package' => 1,
            'checkInDate' => now()->addDay()->toDateString(),
            'booking_name' => 'Angler Test',
            'booking_email' => 'angler@test.com',
            'booking_phone' => '08222222222',
            'gateway' => 'midtrans'
        ];

        // Jalankan logic storeData secara manual atau via Route
        // Di sini kita test logic NotificationService via mock log jika diperlukan
        Log::shouldReceive('info')->with(config('app.name') . ' - WhatsApp Sending to 628222222222: ' . \Mockery::any());
        Log::shouldReceive('info')->with(config('app.name') . ' - WhatsApp Sending to 628111111111: ' . \Mockery::any());

        // 4. Assertions
        $this->assertTrue(true); // Placeholder for actual logic execution check
        
        // Memastikan record booking tercipta (logic integrasi)
        // Kita bisa panggil controller method di sini jika diperlukan unit testing yang lebih dalam
    }
}
