<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'ktp')) {
                $table->string('ktp')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'boat_ownership')) {
                $table->string('boat_ownership')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'driver_license')) {
                $table->string('driver_license')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'self_photo')) {
                $table->string('self_photo')->nullable();
            }
            // Note: document_verified is handled in base migration but checked here for safety
            if (!Schema::hasColumn('vendors', 'document_verified')) {
                $table->tinyInteger('document_verified')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['ktp', 'boat_ownership', 'driving_license', 'self_photo']);
        });
    }
};
