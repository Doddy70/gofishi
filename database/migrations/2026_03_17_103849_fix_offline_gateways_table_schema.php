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
        if (Schema::hasTable('offline_gateways')) {
            Schema::table('offline_gateways', function (Blueprint $table) {
                if (!Schema::hasColumn('offline_gateways', 'name')) {
                    $table->string('name')->after('id');
                }
                if (!Schema::hasColumn('offline_gateways', 'short_description')) {
                    $table->text('short_description')->nullable()->after('name');
                }
                if (!Schema::hasColumn('offline_gateways', 'instructions')) {
                    $table->longText('instructions')->nullable()->after('short_description');
                }
                if (!Schema::hasColumn('offline_gateways', 'status')) {
                    $table->tinyInteger('status')->default(1)->comment('0 -> gateway is deactive, 1 -> gateway is active.')->after('instructions');
                }
                if (!Schema::hasColumn('offline_gateways', 'has_attachment')) {
                    $table->tinyInteger('has_attachment')->default(0)->comment('0 -> do not need attachment, 1 -> need attachment.')->after('status');
                }
                if (!Schema::hasColumn('offline_gateways', 'serial_number')) {
                    $table->mediumInteger('serial_number')->unsigned()->default(1)->after('has_attachment');
                }
            });
        } else {
             Schema::create('offline_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('short_description')->nullable();
                $table->longText('instructions')->nullable();
                $table->tinyInteger('status')->default(1)->comment('0 -> gateway is deactive, 1 -> gateway is active.');
                $table->tinyInteger('has_attachment')->default(0)->comment('0 -> do not need attachment, 1 -> need attachment.');
                $table->mediumInteger('serial_number')->unsigned()->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offline_gateways', function (Blueprint $table) {
            $table->dropColumn(['name', 'short_description', 'instructions', 'status', 'has_attachment', 'serial_number']);
        });
    }
};
