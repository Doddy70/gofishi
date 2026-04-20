<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add hotel_tax_amount to basic_settings if not exists
        if (!Schema::hasColumn('basic_settings', 'hotel_tax_amount')) {
            Schema::table('basic_settings', function (Blueprint $table) {
                $table->decimal('hotel_tax_amount', 10, 3)->default(0)->after('total_earning');
            });
        }

        // Add availability_mode to rooms if not exists
        if (!Schema::hasColumn('rooms', 'availability_mode')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->tinyInteger('availability_mode')->default(1)->after('status')
                      ->comment('1=instant, 2=request approval');
            });
        }

        // Set default tax value
        DB::table('basic_settings')->whereNull('hotel_tax_amount')
            ->orWhere('hotel_tax_amount', 0)
            ->update(['hotel_tax_amount' => 10]);
    }

    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->dropColumn('hotel_tax_amount');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('availability_mode');
        });
    }
};
