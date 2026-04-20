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
        Schema::table('room_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('room_reviews', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_reviews', function (Blueprint $table) {
            $table->dropColumn('hotel_id');
        });
    }
};
