<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY total DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY roomPrice DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY serviceCharge DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY discount DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY tax DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY grand_total DECIMAL(16,2) NOT NULL DEFAULT 0");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY total DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY roomPrice DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY serviceCharge DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY discount DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY tax DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE bookings MODIFY grand_total DECIMAL(8,2) NOT NULL DEFAULT 0");
        }
    }
};
