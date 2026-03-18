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
        Schema::table('seos', function (Blueprint $table) {
            if (!Schema::hasColumn('seos', 'meta_keyword_pricing')) {
                $table->text('meta_keyword_pricing')->nullable();
                $table->text('meta_description_pricing')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_hotels')) {
                $table->text('meta_keyword_hotels')->nullable();
                $table->text('meta_description_hotels')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_rooms')) {
                $table->text('meta_keyword_rooms')->nullable();
                $table->text('meta_description_rooms')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_blog')) {
                $table->text('meta_keyword_blog')->nullable();
                $table->text('meta_description_blog')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_faq')) {
                $table->text('meta_keyword_faq')->nullable();
                $table->text('meta_description_faq')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_contact')) {
                $table->text('meta_keyword_contact')->nullable();
                $table->text('meta_description_contact')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_login')) {
                $table->text('meta_keyword_login')->nullable();
                $table->text('meta_description_login')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_signup')) {
                $table->text('meta_keyword_signup')->nullable();
                $table->text('meta_description_signup')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keyword_forget_password')) {
                $table->text('meta_keyword_forget_password')->nullable();
                $table->text('meta_description_forget_password')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keywords_vendor_login')) {
                $table->text('meta_keywords_vendor_login')->nullable();
                $table->text('meta_description_vendor_login')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keywords_vendor_signup')) {
                $table->text('meta_keywords_vendor_signup')->nullable();
                $table->text('meta_description_vendor_signup')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keywords_vendor_forget_password')) {
                $table->text('meta_keywords_vendor_forget_password')->nullable();
                $table->text('meta_descriptions_vendor_forget_password')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keywords_vendor_page')) {
                $table->text('meta_keywords_vendor_page')->nullable();
                $table->text('meta_description_vendor_page')->nullable();
            }
            if (!Schema::hasColumn('seos', 'meta_keywords_about_page')) {
                $table->text('meta_keywords_about_page')->nullable();
                $table->text('meta_description_about_page')->nullable();
            }
            if (!Schema::hasColumn('seos', 'custome_page_meta_keyword')) {
                $table->text('custome_page_meta_keyword')->nullable();
                $table->text('custome_page_meta_description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seos', function (Blueprint $table) {
            $table->dropColumn([
                'meta_keyword_pricing',
                'meta_description_pricing',
                'meta_keyword_hotels',
                'meta_description_hotels',
                'meta_keyword_rooms',
                'meta_description_rooms',
                'meta_keyword_blog',
                'meta_description_blog',
                'meta_keyword_faq',
                'meta_description_faq',
                'meta_keyword_contact',
                'meta_description_contact',
                'meta_keyword_login',
                'meta_description_login',
                'meta_keyword_signup',
                'meta_description_signup',
                'meta_keyword_forget_password',
                'meta_description_forget_password',
                'meta_keywords_vendor_login',
                'meta_description_vendor_login',
                'meta_keywords_vendor_signup',
                'meta_description_vendor_signup',
                'meta_keywords_vendor_forget_password',
                'meta_descriptions_vendor_forget_password',
                'meta_keywords_vendor_page',
                'meta_description_vendor_page',
                'meta_keywords_about_page',
                'meta_description_about_page',
                'custome_page_meta_keyword',
                'custome_page_meta_description',
            ]);
        });
    }
};
