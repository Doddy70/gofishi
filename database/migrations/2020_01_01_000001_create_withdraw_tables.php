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
        if (!Schema::hasTable('withdraw_payment_methods')) {
            Schema::create('withdraw_payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->decimal('min_limit', 10, 2)->default(0);
                $table->decimal('max_limit', 10, 2)->default(0);
                $table->decimal('fixed_charge', 10, 2)->default(0);
                $table->decimal('percentage_charge', 10, 2)->default(0);
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_payment_methods');
    }
};
