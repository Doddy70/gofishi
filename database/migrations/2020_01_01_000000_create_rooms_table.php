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
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hotel_id')->nullable();
                $table->bigInteger('vendor_id')->nullable();
                $table->string('feature_image', 255)->nullable();
                $table->double('average_rating')->default(0);
                $table->string('latitude', 255)->nullable();
                $table->string('longitude', 255)->nullable();
                $table->bigInteger('status')->nullable();
                $table->bigInteger('bed')->nullable();
                $table->decimal('min_price', 10, 2)->default(0);
                $table->decimal('max_price', 10, 2)->default(0);
                $table->integer('adult')->nullable();
                $table->integer('children')->nullable();
                $table->bigInteger('bathroom')->nullable();
                $table->bigInteger('number_of_rooms_of_this_same_type')->nullable();
                $table->integer('preparation_time')->default(0);
                $table->bigInteger('area')->nullable();
                $table->string('prices', 255)->nullable();
                $table->string('perahu_area_text', 255)->nullable();
                $table->text('additional_service')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
