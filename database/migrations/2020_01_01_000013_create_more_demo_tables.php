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
        $tables = [
            'room_categories', 'hotel_categories', 'faq_categories', 'footer_quick_link_contents', 'subscriber_infos'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->id();
                    $table->integer('language_id')->nullable();
                    $table->string('name')->nullable();
                    $table->integer('status')->default(1);
                    $table->integer('serial_number')->default(0);
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['room_categories', 'hotel_categories', 'faq_categories', 'footer_quick_link_contents', 'subscriber_infos'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
