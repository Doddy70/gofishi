<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vendor_informations')) {
            Schema::table('vendor_informations', function (Blueprint $table) {
                if (!Schema::hasColumn('vendor_informations', 'serial_number')) {
                    $table->integer('serial_number')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vendor_informations') && Schema::hasColumn('vendor_informations', 'serial_number')) {
            Schema::table('vendor_informations', function (Blueprint $table) {
                $table->dropColumn('serial_number');
            });
        }
    }
};
