<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('meet_time_day_1')->nullable()->after('price_day_3');
            $table->string('return_time_day_1')->nullable()->after('meet_time_day_1');
            $table->string('meet_time_day_2')->nullable()->after('return_time_day_1');
            $table->string('return_time_day_2')->nullable()->after('meet_time_day_2');
            $table->string('meet_time_day_3')->nullable()->after('return_time_day_2');
            $table->string('return_time_day_3')->nullable()->after('meet_time_day_3');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'meet_time_day_1',
                'return_time_day_1',
                'meet_time_day_2',
                'return_time_day_2',
                'meet_time_day_3',
                'return_time_day_3',
            ]);
        });
    }
};
