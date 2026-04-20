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
        if (Schema::hasTable('hotel_counters')) {
            Schema::table('hotel_counters', function (Blueprint $table) {
                if (!Schema::hasColumn('hotel_counters', 'hotel_id')) {
                    $table->unsignedBigInteger('hotel_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('hotel_counters', 'key')) {
                    $table->integer('key')->nullable()->after('hotel_id');
                }
            });
        }

        if (Schema::hasTable('hotel_counter_contents')) {
            Schema::table('hotel_counter_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('hotel_counter_contents', 'language_id')) {
                    $table->unsignedBigInteger('language_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('hotel_counter_contents', 'hotel_counter_id')) {
                    $table->unsignedBigInteger('hotel_counter_id')->nullable()->after('language_id');
                }
                if (!Schema::hasColumn('hotel_counter_contents', 'label')) {
                    $table->string('label')->nullable()->after('hotel_counter_id');
                }
                if (!Schema::hasColumn('hotel_counter_contents', 'value')) {
                    $table->string('value')->nullable()->after('label');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_counters', function (Blueprint $table) {
            $table->dropColumn(['hotel_id', 'key']);
        });
        Schema::table('hotel_counter_contents', function (Blueprint $table) {
            $table->dropColumn(['language_id', 'hotel_counter_id', 'label', 'value']);
        });
    }
};
