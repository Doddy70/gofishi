<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('online_gateways')
            ->where('keyword', 'midtrans')
            ->update(['name' => 'Midtrans (QRIS, VA, dll)']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('online_gateways')
            ->where('keyword', 'midtrans')
            ->update(['name' => 'Midtrans']);
    }
};
