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
        Schema::table('blog_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_categories', 'language_id')) {
                $table->bigInteger('language_id')->unsigned()->after('id')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'name')) {
                $table->string('name', 255)->after('language_id')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'slug')) {
                $table->string('slug', 255)->after('name')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'status')) {
                $table->tinyInteger('status')->unsigned()->default(1)->after('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            if (Schema::hasColumn('blog_categories', 'language_id')) {
                $table->dropColumn('language_id');
            }
            if (Schema::hasColumn('blog_categories', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('blog_categories', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('blog_categories', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
