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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'transcation_id')) {
                $table->string('transcation_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('transactions', 'booking_id')) {
                $table->integer('booking_id')->nullable()->after('transcation_id');
            }
            if (!Schema::hasColumn('transactions', 'transcation_type')) {
                $table->string('transcation_type')->nullable()->after('booking_id');
            }
            if (!Schema::hasColumn('transactions', 'user_id')) {
                $table->integer('user_id')->nullable()->after('transcation_type');
            }
            if (!Schema::hasColumn('transactions', 'vendor_id')) {
                $table->integer('vendor_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->tinyInteger('payment_status')->default(0)->after('vendor_id');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('transactions', 'grand_total')) {
                $table->decimal('grand_total', 20, 2)->default(0)->after('payment_method');
            }
            if (!Schema::hasColumn('transactions', 'commission')) {
                $table->decimal('commission', 20, 2)->default(0)->after('grand_total');
            }
            if (!Schema::hasColumn('transactions', 'pre_balance')) {
                $table->decimal('pre_balance', 20, 2)->nullable()->after('commission');
            }
            if (!Schema::hasColumn('transactions', 'after_balance')) {
                $table->decimal('after_balance', 20, 2)->nullable()->after('pre_balance');
            }
            if (!Schema::hasColumn('transactions', 'gateway_type')) {
                $table->string('gateway_type')->nullable()->after('after_balance');
            }
            if (!Schema::hasColumn('transactions', 'currency_symbol')) {
                $table->string('currency_symbol')->nullable()->after('gateway_type');
            }
            if (!Schema::hasColumn('transactions', 'currency_symbol_position')) {
                $table->string('currency_symbol_position')->nullable()->after('currency_symbol');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transcation_id', 'booking_id', 'transcation_type', 'user_id', 'vendor_id',
                'payment_status', 'payment_method', 'grand_total', 'commission',
                'pre_balance', 'after_balance', 'gateway_type', 'currency_symbol', 'currency_symbol_position'
            ]);
        });
    }
};
