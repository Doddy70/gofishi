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
        if (Schema::hasTable('hotel_contents')) {
            Schema::table('hotel_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('hotel_contents', 'country_id')) {
                    $table->unsignedBigInteger('country_id')->nullable()->after('category_id');
                }
                if (!Schema::hasColumn('hotel_contents', 'state_id')) {
                    $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
                }
                if (!Schema::hasColumn('hotel_contents', 'city_id')) {
                    $table->unsignedBigInteger('city_id')->nullable()->after('state_id');
                }
                if (!Schema::hasColumn('hotel_contents', 'amenities')) {
                    $table->text('amenities')->nullable()->after('address');
                }
                if (!Schema::hasColumn('hotel_contents', 'meta_keyword')) {
                    $table->text('meta_keyword')->nullable()->after('description');
                }
                if (!Schema::hasColumn('hotel_contents', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_keyword');
                }
            });
        }

        if (Schema::hasTable('room_contents')) {
            Schema::table('room_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('room_contents', 'amenities')) {
                    $table->text('amenities')->nullable()->after('address');
                }
                if (!Schema::hasColumn('room_contents', 'meta_keyword')) {
                    $table->text('meta_keyword')->nullable()->after('description');
                }
                if (!Schema::hasColumn('room_contents', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_keyword');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_contents', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'state_id', 'city_id', 'amenities', 'meta_keyword', 'meta_description']);
        });
        Schema::table('room_contents', function (Blueprint $table) {
            $table->dropColumn(['amenities', 'meta_keyword', 'meta_description']);
        });
    }
};
