<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('ktp', 'ktp_file');
            $table->renameColumn('boat_ownership', 'boat_ownership_file');
            $table->renameColumn('driver_license', 'driving_license_file');
            $table->renameColumn('self_photo', 'self_photo_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('ktp_file', 'ktp');
            $table->renameColumn('boat_ownership_file', 'boat_ownership');
            $table->renameColumn('driving_license_file', 'driver_license');
            $table->renameColumn('self_photo_file', 'self_photo');
        });
    }
};
