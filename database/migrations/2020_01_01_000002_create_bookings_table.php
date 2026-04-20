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
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('vendor_id')->nullable();
                $table->bigInteger('room_id')->nullable();
                $table->bigInteger('hotel_id')->nullable();
                $table->decimal('total', 10, 2)->default(0);
                $table->decimal('roomPrice', 10, 2)->default(0);
                $table->decimal('serviceCharge', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2)->default(0);
                $table->integer('payment_status')->default(0);
                $table->string('payment_method')->nullable();
                $table->string('booking_name')->nullable();
                $table->string('booking_email')->nullable();
                $table->string('booking_phone')->nullable();
                $table->text('booking_address')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
