<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY min_limit DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY max_limit DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY fixed_charge DECIMAL(16,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY percentage_charge DECIMAL(8,2) NOT NULL DEFAULT 0");
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN in the same way; skip for now.
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY min_limit DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY max_limit DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY fixed_charge DECIMAL(8,2) NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE withdraw_payment_methods MODIFY percentage_charge DECIMAL(6,2) NOT NULL DEFAULT 0");
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN in the same way; skip for now.
        }
    }
};
