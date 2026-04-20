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
        Schema::table('room_reviews', function (Blueprint $table) {
            $table->integer('cleanliness')->default(5)->after('rating');
            $table->integer('accuracy')->default(5)->after('cleanliness');
            $table->integer('check_in')->default(5)->after('accuracy');
            $table->integer('communication')->default(5)->after('check_in');
            $table->integer('location')->default(5)->after('communication');
            $table->integer('value')->default(5)->after('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_reviews', function (Blueprint $table) {
            $table->dropColumn(['cleanliness', 'accuracy', 'check_in', 'communication', 'location', 'value']);
        });
    }
};
