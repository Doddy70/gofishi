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
        if (!Schema::hasTable('online_gateways')) {
            Schema::create('online_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('keyword')->nullable();
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
        Schema::dropIfExists('online_gateways');
    }
};
