<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. CUSTOM SECTIONS
        if (!Schema::hasTable('custom_sections')) {
            Schema::create('custom_sections', function (Blueprint $table) {
                $table->id();
                $table->string('page_type')->nullable();
                $table->string('section_name')->nullable();
                $table->integer('status')->default(1);
                $table->integer('serial_number')->default(0);
                $table->timestamps();
            });
        }

        // 2. FEATURES
        if (!Schema::hasTable('features')) {
            Schema::create('features', function (Blueprint $table) {
                $table->id();
                $table->integer('language_id')->nullable();
                $table->string('icon')->nullable();
                $table->integer('serial_number')->default(0);
                $table->timestamps();
            });
        }

        // 3. SEOS
        if (!Schema::hasTable('seos')) {
            Schema::create('seos', function (Blueprint $table) {
                $table->id();
                $table->integer('language_id')->nullable();
                $table->string('meta_keyword_home')->nullable();
                $table->text('meta_description_home')->nullable();
                $table->timestamps();
            });
        }

        // 4. SECTIONS
        if (!Schema::hasTable('sections')) {
            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->integer('language_id')->nullable();
                $table->integer('footer_section_status')->default(1);
                $table->text('custom_section_status')->nullable();
                $table->timestamps();
            });
        }

        // 5. SOCIAL MEDIA
        if (!Schema::hasTable('social_media')) {
            Schema::create('social_media', function (Blueprint $table) {
                $table->id();
                $table->string('icon')->nullable();
                $table->string('url')->nullable();
                $table->integer('serial_number')->default(0);
                $table->timestamps();
            });
        }

        // 6. SUBSCRIBERS
        if (!Schema::hasTable('subscribers')) {
            Schema::create('subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email_id')->unique();
                $table->timestamps();
            });
        }

        // 7. REMAINING PLACEHOLDERS
        $tables = [
            'about_us', 'additional_services', 'additional_service_contents', 
            'advertisements', 'amenities', 'banners', 'benifits', 
            'blog_categories', 'booking_hours', 'conversations', 'cookie_alerts', 
            'counter_informations', 'faqs', 'faq_contents', 'feature_contents',
            'footer_quick_links', 'footer_texts', 'hero_sections', 
            'hourly_room_prices', 'menus', 'offline_gateways', 
            'pages', 'page_contents', 'partners', 'announcement_popups',
            'cookie_alert_contents', 'faq_informations', 'footer_quick_link_contents',
            'testimonial_contents', 'transactions'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $t) use ($table) {
                    $t->id();
                    if (str_contains($table, 'content') || in_array($table, ['about_us', 'vendor_infos', 'testimonials', 'benifits', 'partners'])) {
                        $t->integer('language_id')->nullable();
                    }
                    $t->timestamps();
                });
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void {}
};
