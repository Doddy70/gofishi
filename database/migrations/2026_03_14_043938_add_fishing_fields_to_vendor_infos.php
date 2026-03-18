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
        Schema::table('vendor_infos', function (Blueprint $blueprint) {
            $blueprint->string('license_number')->nullable()->after('address')->comment('U.S. Coast Guard or Local License Number');
            $blueprint->text('specializations')->nullable()->after('license_number')->comment('JSON array of fishing techniques like Fly Fishing, Saltwater, etc.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_infos', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['license_number', 'specializations']);
        });
    }
};
