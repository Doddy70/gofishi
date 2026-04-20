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
        Schema::table('basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('basic_settings', 'google_adsense_publisher_id')) {
                $table->string('google_adsense_publisher_id')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'smtp_status')) {
                $table->tinyInteger('smtp_status')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'smtp_host')) {
                $table->string('smtp_host')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'smtp_port')) {
                $table->integer('smtp_port')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'encryption')) {
                $table->string('encryption', 50)->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'smtp_username')) {
                $table->string('smtp_username')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'smtp_password')) {
                $table->string('smtp_password')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'from_mail')) {
                $table->string('from_mail')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'from_name')) {
                $table->string('from_name')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'to_mail')) {
                $table->string('to_mail')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'admin_theme_version')) {
                $table->string('admin_theme_version', 10)->default('light');
            }
        });
    }

    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_adsense_publisher_id',
                'smtp_status',
                'smtp_host',
                'smtp_port',
                'encryption',
                'smtp_username',
                'smtp_password',
                'from_mail',
                'from_name',
                'to_mail',
                'admin_theme_version'
            ]);
        });
    }
};
