<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perahu_daily_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedInteger('day_count');
            $table->unsignedBigInteger('price')->nullable();
            $table->string('meet_time')->nullable();
            $table->string('return_time')->nullable();
            $table->string('area_text')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index('room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perahu_daily_rates');
    }
};
