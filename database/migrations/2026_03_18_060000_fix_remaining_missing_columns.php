<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix amenities table - missing core columns
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'language_id')) {
                $table->bigInteger('language_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('amenities', 'title')) {
                $table->string('title')->nullable()->after('language_id');
            }
            if (!Schema::hasColumn('amenities', 'icon')) {
                $table->string('icon')->nullable()->after('title');
            }
            if (!Schema::hasColumn('amenities', 'serial_number')) {
                $table->integer('serial_number')->default(0)->after('icon');
            }
        });

        // 2. Add serial_number to multiple tables that are ordered by it in code
        $tablesToUpdate = [
            'faqs',
            'blog_categories',
            'languages',
            'memberships',
            'packages',
            'rooms',
            'hotels'
        ];

        foreach ($tablesToUpdate as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'serial_number')) {
                        $table->integer('serial_number')->default(0);
                    }
                });
            }
        }

        // 3. Fix page_contents - missing page_id
        if (Schema::hasTable('page_contents')) {
            Schema::table('page_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('page_contents', 'page_id')) {
                    $table->bigInteger('page_id')->nullable()->after('id');
                }
            });
        }

        // 4. Fix blog_informations - missing blog_category_id
        if (Schema::hasTable('blog_informations')) {
            Schema::table('blog_informations', function (Blueprint $table) {
                if (!Schema::hasColumn('blog_informations', 'blog_category_id')) {
                    $table->bigInteger('blog_category_id')->nullable()->after('blog_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropColumn(['language_id', 'title', 'icon', 'serial_number']);
        });

        $tablesToUpdate = [
            'faqs',
            'blog_categories',
            'languages',
            'memberships',
            'packages',
            'rooms',
            'hotels'
        ];

        foreach ($tablesToUpdate as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'serial_number')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('serial_number');
                });
            }
        }

        if (Schema::hasTable('page_contents') && Schema::hasColumn('page_contents', 'page_id')) {
            Schema::table('page_contents', function (Blueprint $table) {
                $table->dropColumn('page_id');
            });
        }

        if (Schema::hasTable('blog_informations') && Schema::hasColumn('blog_informations', 'blog_category_id')) {
            Schema::table('blog_informations', function (Blueprint $table) {
                $table->dropColumn('blog_category_id');
            });
        }
    }
};
