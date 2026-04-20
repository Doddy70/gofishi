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
        // 1. Fix 'pages' table
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(1);
                $table->integer('serial_number')->default(0);
                $table->timestamps();
            });
        } else {
             Schema::table('pages', function (Blueprint $table) {
                if (!Schema::hasColumn('pages', 'status')) {
                    $table->integer('status')->default(1)->after('id');
                }
                if (!Schema::hasColumn('pages', 'serial_number')) {
                    $table->integer('serial_number')->default(0)->after('status');
                }
            });
        }

        // 2. Fix 'page_contents' table
        if (!Schema::hasTable('page_contents')) {
            Schema::create('page_contents', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('page_id')->nullable();
                $table->bigInteger('language_id')->nullable();
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->longText('content')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('page_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('page_contents', 'page_id')) {
                    $table->bigInteger('page_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('page_contents', 'language_id')) {
                    $table->bigInteger('language_id')->nullable()->after('page_id');
                }
                if (!Schema::hasColumn('page_contents', 'title')) {
                    $table->string('title')->nullable()->after('language_id');
                }
                if (!Schema::hasColumn('page_contents', 'slug')) {
                    $table->string('slug')->nullable()->after('title');
                }
                if (!Schema::hasColumn('page_contents', 'content')) {
                    $table->longText('content')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('page_contents', 'meta_keywords')) {
                    $table->text('meta_keywords')->nullable()->after('content');
                }
                if (!Schema::hasColumn('page_contents', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_keywords');
                }
            });
        }

        // 3. Ensure 'footer_texts' table exists for AppServiceProvider
        if (!Schema::hasTable('footer_texts')) {
            Schema::create('footer_texts', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('language_id')->nullable();
                $table->text('about_company')->nullable();
                $table->text('copyright_text')->nullable();
                $table->timestamps();
            });
        }
        
        // 4. Ensure 'quick_links' table exists for AppServiceProvider
         if (!Schema::hasTable('quick_links')) {
            Schema::create('quick_links', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('language_id')->nullable();
                $table->string('title')->nullable();
                $table->string('url')->nullable();
                $table->integer('serial_number')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop for safety
    }
};
