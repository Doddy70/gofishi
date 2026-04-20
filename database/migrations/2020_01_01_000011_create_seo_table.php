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
        if (!Schema::hasTable('seos')) {
            Schema::create('seos', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('language_id')->nullable();
                $table->string('meta_keyword_home')->nullable();
                $table->text('meta_description_home')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
