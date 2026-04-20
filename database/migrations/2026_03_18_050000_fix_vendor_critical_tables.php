<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix booking_hours - missing: hour, serial_number
        Schema::table('booking_hours', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_hours', 'hour')) {
                $table->bigInteger('hour')->nullable()->after('id');
            }
            if (!Schema::hasColumn('booking_hours', 'serial_number')) {
                $table->bigInteger('serial_number')->nullable()->after('hour');
            }
        });

        // Seed default booking hours if empty
        if (DB::table('booking_hours')->count() === 0) {
            DB::table('booking_hours')->insert([
                ['hour' => 2,  'serial_number' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['hour' => 6,  'serial_number' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['hour' => 9,  'serial_number' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['hour' => 12, 'serial_number' => 4, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Fix hourly_room_prices - missing many columns
        Schema::table('hourly_room_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('hourly_room_prices', 'vendor_id')) {
                $table->bigInteger('vendor_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('hourly_room_prices', 'hotel_id')) {
                $table->bigInteger('hotel_id')->nullable()->after('vendor_id');
            }
            if (!Schema::hasColumn('hourly_room_prices', 'room_id')) {
                $table->bigInteger('room_id')->nullable()->after('hotel_id');
            }
            if (!Schema::hasColumn('hourly_room_prices', 'hour_id')) {
                $table->bigInteger('hour_id')->nullable()->after('room_id');
            }
            if (!Schema::hasColumn('hourly_room_prices', 'hour')) {
                $table->integer('hour')->nullable()->after('hour_id');
            }
            if (!Schema::hasColumn('hourly_room_prices', 'price')) {
                $table->double('price')->nullable()->after('hour');
            }
        });

        // 3. Create room_bookings if missing (alias for perahu bookings)
        if (!Schema::hasTable('room_bookings')) {
            Schema::create('room_bookings', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('vendor_id')->nullable();
                $table->bigInteger('hotel_id')->nullable();
                $table->bigInteger('room_id')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->string('booking_number')->nullable();
                $table->string('check_in')->nullable();
                $table->string('check_out')->nullable();
                $table->integer('number_of_days')->nullable();
                $table->integer('number_of_children')->default(0)->nullable();
                $table->integer('number_of_guests')->default(1)->nullable();
                $table->double('room_price')->nullable();
                $table->double('subtotal')->nullable();
                $table->double('total_price')->nullable();
                $table->double('tax_charge')->nullable();
                $table->double('coupon_discount')->nullable();
                $table->string('coupon_code')->nullable();
                $table->double('admin_commission')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default('pending');
                $table->string('booking_status')->default('pending');
                $table->string('currency_code')->nullable();
                $table->string('currency_symbol')->nullable();
                $table->string('currency_symbol_position')->nullable();
                $table->string('gateway_type')->nullable();
                $table->string('invoice')->nullable();
                $table->text('customer_info')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create room_booking_items if missing
        if (!Schema::hasTable('room_booking_items')) {
            Schema::create('room_booking_items', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('booking_id')->nullable();
                $table->bigInteger('room_id')->nullable();
                $table->string('name')->nullable();
                $table->double('price')->nullable();
                $table->integer('quantity')->nullable();
                $table->double('subtotal')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('booking_hours', function (Blueprint $table) {
            $table->dropColumn(['hour', 'serial_number']);
        });
        Schema::table('hourly_room_prices', function (Blueprint $table) {
            $table->dropColumn(['vendor_id', 'hotel_id', 'room_id', 'hour_id', 'hour', 'price']);
        });
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('room_booking_items');
    }
};
