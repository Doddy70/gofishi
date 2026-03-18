<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('price_day_1')->nullable()->after('daily_price');
            $table->unsignedBigInteger('price_day_2')->nullable()->after('price_day_1');
            $table->unsignedBigInteger('price_day_3')->nullable()->after('price_day_2');

            if (Schema::hasColumn('rooms', 'perahu_facilities_text')) {
                $table->dropColumn('perahu_facilities_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['price_day_1', 'price_day_2', 'price_day_3']);
            $table->text('perahu_facilities_text')->nullable()->after('perahu_area_text');
        });
    }
};
