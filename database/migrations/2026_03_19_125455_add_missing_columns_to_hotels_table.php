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
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'logo')) {
                $table->string('logo', 255)->nullable();
            }
            if (!Schema::hasColumn('hotels', 'max_price')) {
                $table->float('max_price')->nullable()->default(0);
            }
            if (!Schema::hasColumn('hotels', 'min_price')) {
                $table->float('min_price')->nullable()->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['logo', 'max_price', 'min_price']);
        });
    }
};
