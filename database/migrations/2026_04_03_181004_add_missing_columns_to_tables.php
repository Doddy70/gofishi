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
        Schema::table('vendor_infos', function (Blueprint $table) {
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
        });

        Schema::table('room_coupons', function (Blueprint $table) {
            $table->text('rooms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_infos', function (Blueprint $table) {
            $table->dropColumn(['state', 'zip_code']);
        });

        Schema::table('room_coupons', function (Blueprint $table) {
            $table->dropColumn('rooms');
        });
    }
};
