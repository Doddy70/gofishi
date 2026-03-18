<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('basic_settings', 'hotel_view')) {
                $table->integer('hotel_view')->default(1)->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'room_view')) {
                $table->integer('room_view')->default(1)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            if (Schema::hasColumn('basic_settings', 'hotel_view')) {
                $table->dropColumn('hotel_view');
            }
            if (Schema::hasColumn('basic_settings', 'room_view')) {
                $table->dropColumn('room_view');
            }
        });
    }
};
