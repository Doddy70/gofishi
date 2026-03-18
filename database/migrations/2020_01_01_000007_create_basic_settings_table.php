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
        if (!Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('uniqid')->default(12345);
                $table->string('theme_version')->default('1');
                $table->string('website_title')->nullable();
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('breadcrumb')->nullable();
                $table->string('maintenance_img')->nullable();
                $table->text('maintenance_msg')->nullable();
                $table->string('footer_logo')->nullable();
                $table->string('footer_background_image')->nullable();
                $table->string('email_address')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
                $table->string('primary_color')->nullable();
                $table->integer('whatsapp_status')->default(0);
                $table->string('whatsapp_number')->nullable();
                $table->string('whatsapp_header_title')->nullable();
                $table->integer('whatsapp_popup_status')->default(0);
                $table->text('whatsapp_popup_message')->nullable();
                $table->integer('tawkto_status')->default(0);
                $table->text('tawkto_direct_chat_link')->nullable();
                $table->string('base_currency_text')->default('IDR');
                $table->string('base_currency_text_position')->default('left');
                $table->string('base_currency_symbol')->default('Rp');
                $table->string('base_currency_symbol_position')->default('left');
                $table->string('base_currency_rate')->default('1');
                $table->string('hero_section_video_url')->nullable();
                $table->integer('preloader_status')->default(0);
                $table->string('preloader')->nullable();
                $table->string('google_map_api_key')->nullable();
                $table->integer('google_map_api_key_status')->default(0);
                $table->string('time_format')->default('24h');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_settings');
    }
};
