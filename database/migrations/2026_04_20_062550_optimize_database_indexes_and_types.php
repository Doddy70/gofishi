<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Optimize Rooms Table
        Schema::table('rooms', function (Blueprint $table) {
            // Add indexes for foreign keys
            if (Schema::hasColumn('rooms', 'vendor_id')) {
                $table->index('vendor_id', 'idx_rooms_vendor_id');
            }
            if (Schema::hasColumn('rooms', 'hotel_id')) {
                $table->index('hotel_id', 'idx_rooms_hotel_id');
            }
            
            // Optimize Type: status (was bigInteger)
            // Note: We use change() which requires doctrine/dbal, 
            // but for older Laravel or strict setups we might use raw SQL.
        });
        
        // Use raw SQL for type optimization to ensure accuracy
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status TINYINT DEFAULT 1");

        // 2. Optimize Room Contents Table
        if (Schema::hasTable('room_contents')) {
            Schema::table('room_contents', function (Blueprint $table) {
                if (Schema::hasColumn('room_contents', 'room_id')) {
                    $table->index('room_id', 'idx_room_contents_room_id');
                }
                if (Schema::hasColumn('room_contents', 'language_id')) {
                    $table->index('language_id', 'idx_room_contents_language_id');
                }
                // Combined index for localized lookups
                $table->index(['room_id', 'language_id'], 'idx_room_contents_composite');
            });
        }

        // 3. Optimize Bookings Table
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'vendor_id')) {
                    $table->index('vendor_id', 'idx_bookings_vendor_id');
                }
                if (Schema::hasColumn('bookings', 'room_id')) {
                    $table->index('room_id', 'idx_bookings_room_id');
                }
                if (Schema::hasColumn('bookings', 'customer_id')) {
                    $table->index('customer_id', 'idx_bookings_customer_id');
                }
                $table->index('payment_status', 'idx_bookings_payment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex('idx_rooms_vendor_id');
            $table->dropIndex('idx_rooms_hotel_id');
        });
        
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status BIGINT");

        if (Schema::hasTable('room_contents')) {
            Schema::table('room_contents', function (Blueprint $table) {
                $table->dropIndex('idx_room_contents_room_id');
                $table->dropIndex('idx_room_contents_language_id');
                $table->dropIndex('idx_room_contents_composite');
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex('idx_bookings_vendor_id');
                $table->dropIndex('idx_bookings_room_id');
                $table->dropIndex('idx_bookings_customer_id');
                $table->dropIndex('idx_bookings_payment_status');
            });
        }
    }
};
