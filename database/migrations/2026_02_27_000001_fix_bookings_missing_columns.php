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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'roomPrice')) {
                $table->decimal('roomPrice', 10, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('bookings', 'serviceCharge')) {
                $table->decimal('serviceCharge', 10, 2)->default(0)->after('roomPrice');
            }
            if (!Schema::hasColumn('bookings', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('serviceCharge');
            }
            if (!Schema::hasColumn('bookings', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['roomPrice', 'serviceCharge', 'discount', 'tax']);
        });
    }
};
