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
            if (!Schema::hasColumn('basic_settings', 'google_recaptcha_status')) {
                $table->integer('google_recaptcha_status')->default(0);
            }
            if (!Schema::hasColumn('basic_settings', 'google_recaptcha_site_key')) {
                $table->string('google_recaptcha_site_key')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'google_recaptcha_secret_key')) {
                $table->string('google_recaptcha_secret_key')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'facebook_login_status')) {
                $table->tinyInteger('facebook_login_status')->unsigned()->default(1);
            }
            if (!Schema::hasColumn('basic_settings', 'facebook_app_id')) {
                $table->string('facebook_app_id')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'facebook_app_secret')) {
                $table->string('facebook_app_secret')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'google_login_status')) {
                $table->tinyInteger('google_login_status')->unsigned()->default(1);
            }
            if (!Schema::hasColumn('basic_settings', 'google_client_id')) {
                $table->string('google_client_id')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'google_client_secret')) {
                $table->string('google_client_secret')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'google_map_api_key')) {
                $table->string('google_map_api_key')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'tawkto_status')) {
                $table->tinyInteger('tawkto_status')->unsigned()->default(0);
            }
            if (!Schema::hasColumn('basic_settings', 'vendor_admin_approval')) {
                $table->integer('vendor_admin_approval')->default(0);
            }
            if (!Schema::hasColumn('basic_settings', 'vendor_email_verification')) {
                $table->integer('vendor_email_verification')->default(0);
            }
            if (!Schema::hasColumn('basic_settings', 'admin_approval_notice')) {
                $table->text('admin_approval_notice')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'expiration_reminder')) {
                $table->integer('expiration_reminder')->default(3);
            }
            if (!Schema::hasColumn('basic_settings', 'timezone')) {
                $table->string('timezone')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'radius')) {
                $table->integer('radius')->default(10);
            }
            if (!Schema::hasColumn('basic_settings', 'whatsapp_status')) {
                $table->tinyInteger('whatsapp_status')->unsigned()->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number', 20)->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'whatsapp_header_title')) {
                $table->string('whatsapp_header_title')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'whatsapp_popup_status')) {
                $table->tinyInteger('whatsapp_popup_status')->unsigned()->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'whatsapp_popup_message')) {
                $table->text('whatsapp_popup_message')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'maintenance_status')) {
                $table->tinyInteger('maintenance_status')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'maintenance_msg')) {
                $table->text('maintenance_msg')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'maintenance_img')) {
                $table->string('maintenance_img')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'footer_logo')) {
                $table->string('footer_logo')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'footer_background_image')) {
                $table->string('footer_background_image')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'notification_image')) {
                $table->string('notification_image')->nullable();
            }
            if (!Schema::hasColumn('basic_settings', 'guest_checkout_status')) {
                $table->tinyInteger('guest_checkout_status')->unsigned()->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
