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
        Schema::table('vendor_infos', function (Blueprint $table) {
            $table->string('occupation')->nullable()->after('specializations');
            $table->string('languages')->nullable()->after('occupation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_infos', function (Blueprint $table) {
            $table->dropColumn(['occupation', 'languages']);
        });
    }
};
