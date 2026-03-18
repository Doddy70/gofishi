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
        if (!Schema::hasColumn('room_categories', 'slug')) {
            Schema::table('room_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('hotel_categories', 'slug')) {
            Schema::table('hotel_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('hotel_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
