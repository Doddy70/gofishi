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
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('area_day_1')->nullable()->after('return_time_day_1');
            $table->string('area_day_2')->nullable()->after('return_time_day_2');
            $table->string('area_day_3')->nullable()->after('return_time_day_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['area_day_1', 'area_day_2', 'area_day_3']);
        });
    }
};
