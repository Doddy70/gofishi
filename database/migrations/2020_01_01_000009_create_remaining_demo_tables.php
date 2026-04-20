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
            'hotels', 'hotel_contents', 'hotel_images', 'hotel_counters', 'hotel_counter_contents',
            'room_contents', 'room_images', 'hourly_room_prices',
            'transactions', 'memberships',
            'blog_informations'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->id();
                    $table->timestamps();
                    // Basic placeholder columns to avoid seeder failure
                    // Seeder will use raw DB calls or Eloquent where needed
                });
            }
        }

        // Specific columns for required tables
        Schema::table('hotels', function (Blueprint $table) {
            $table->bigInteger('vendor_id')->nullable()->after('id');
            $table->integer('status')->default(1)->after('vendor_id');
            $table->integer('stars')->default(0)->after('status');
            $table->double('average_rating')->default(0)->after('stars');
            $table->string('latitude')->nullable()->after('average_rating');
            $table->string('longitude')->nullable()->after('latitude');
        });

        Schema::table('hotel_contents', function (Blueprint $table) {
            $table->bigInteger('hotel_id')->nullable()->after('id');
            $table->bigInteger('language_id')->nullable()->after('hotel_id');
            $table->string('title')->nullable()->after('language_id');
            $table->string('slug')->nullable()->after('title');
            $table->text('address')->nullable()->after('slug');
            $table->text('description')->nullable()->after('address');
            $table->bigInteger('category_id')->nullable()->after('description');
        });

        Schema::table('room_contents', function (Blueprint $table) {
            $table->bigInteger('room_id')->nullable()->after('id');
            $table->bigInteger('language_id')->nullable()->after('room_id');
            $table->string('title')->nullable()->after('language_id');
            $table->string('slug')->nullable()->after('title');
            $table->text('address')->nullable()->after('slug');
            $table->text('description')->nullable()->after('address');
            $table->bigInteger('room_category')->nullable()->after('description');
        });

        Schema::table('blog_informations', function (Blueprint $table) {
            $table->bigInteger('blog_id')->nullable()->after('id');
            $table->bigInteger('language_id')->nullable()->after('blog_id');
            $table->string('title')->nullable()->after('language_id');
            $table->string('slug')->nullable()->after('title');
            $table->text('content')->nullable()->after('slug');
            $table->string('author')->nullable()->after('content');
        });

        Schema::table('room_images', function (Blueprint $table) {
            $table->bigInteger('room_id')->nullable()->after('id');
            $table->string('image')->nullable()->after('room_id');
        });

        Schema::table('hotel_images', function (Blueprint $table) {
            $table->bigInteger('hotel_id')->nullable()->after('id');
            $table->string('image')->nullable()->after('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'hotels', 'hotel_contents', 'hotel_images', 'hotel_counters', 'hotel_counter_contents',
            'room_contents', 'room_images', 'hourly_room_prices',
            'transactions', 'memberships',
            'blog_informations'
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
