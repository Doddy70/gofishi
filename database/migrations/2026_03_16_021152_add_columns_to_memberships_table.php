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
        Schema::table('memberships', function (Blueprint $table) {
            if (!Schema::hasColumn('memberships', 'vendor_id')) {
                $table->bigInteger('vendor_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('memberships', 'package_id')) {
                $table->bigInteger('package_id')->nullable()->after('vendor_id');
            }
            if (!Schema::hasColumn('memberships', 'price')) {
                $table->double('price')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('memberships', 'currency')) {
                $table->string('currency')->nullable()->after('price');
            }
            if (!Schema::hasColumn('memberships', 'currency_symbol')) {
                $table->string('currency_symbol')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('memberships', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency_symbol');
            }
            if (!Schema::hasColumn('memberships', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('memberships', 'status')) {
                $table->integer('status')->default(0)->after('transaction_id');
            }
            if (!Schema::hasColumn('memberships', 'is_trial')) {
                $table->tinyInteger('is_trial')->default(0)->after('status');
            }
            if (!Schema::hasColumn('memberships', 'trial_days')) {
                $table->integer('trial_days')->default(0)->after('is_trial');
            }
            if (!Schema::hasColumn('memberships', 'receipt')) {
                $table->longText('receipt')->nullable()->after('trial_days');
            }
            if (!Schema::hasColumn('memberships', 'transaction_details')) {
                $table->longText('transaction_details')->nullable()->after('receipt');
            }
            if (!Schema::hasColumn('memberships', 'settings')) {
                $table->longText('settings')->nullable()->after('transaction_details');
            }
            if (!Schema::hasColumn('memberships', 'start_date')) {
                $table->date('start_date')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('memberships', 'expire_date')) {
                $table->date('expire_date')->nullable()->after('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_id', 'package_id', 'price', 'currency', 'currency_symbol', 
                'payment_method', 'transaction_id', 'status', 'is_trial', 
                'trial_days', 'receipt', 'transaction_details', 'settings', 
                'start_date', 'expire_date'
            ]);
        });
    }
};
