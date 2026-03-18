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
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('check_in_date')->nullable()->after('hotel_id');
            $table->string('check_in_time')->nullable()->after('check_in_date');
            $table->date('check_out_date')->nullable()->after('check_in_time');
            $table->string('check_out_time')->nullable()->after('check_out_date');
            $table->dateTime('check_in_date_time')->nullable()->after('check_out_time');
            $table->dateTime('check_out_date_time')->nullable()->after('check_in_date_time');
            $table->integer('preparation_time')->default(0)->after('check_out_date_time');
            $table->string('next_booking_time')->nullable()->after('preparation_time');
            $table->integer('hour')->default(0)->after('next_booking_time');
            $table->integer('adult')->default(0)->after('hour');
            $table->integer('children')->default(0)->after('adult');
            $table->text('additional_service')->nullable()->after('children');
            $table->text('service_details')->nullable()->after('additional_service');
            $table->string('gateway_type')->nullable()->after('payment_method');
            $table->string('order_status')->nullable()->after('payment_status');
            $table->string('attachment')->nullable()->after('order_status');
            $table->string('invoice')->nullable()->after('attachment');
            $table->string('currency_text')->nullable()->after('grand_total');
            $table->string('currency_text_position')->nullable()->after('currency_text');
            $table->string('currency_symbol')->nullable()->after('currency_text_position');
            $table->string('currency_symbol_position')->nullable()->after('currency_symbol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time',
                'check_in_date_time', 'check_out_date_time', 'preparation_time',
                'next_booking_time', 'hour', 'adult', 'children',
                'additional_service', 'service_details', 'gateway_type',
                'order_status', 'attachment', 'invoice', 'currency_text',
                'currency_text_position', 'currency_symbol', 'currency_symbol_position'
            ]);
        });
    }
};
