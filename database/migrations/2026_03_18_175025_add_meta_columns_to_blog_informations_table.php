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
        if (Schema::hasTable('blog_informations')) {
            Schema::table('blog_informations', function (Blueprint $table) {
                if (!Schema::hasColumn('blog_informations', 'meta_keywords')) {
                    $table->text('meta_keywords')->nullable();
                }
                if (!Schema::hasColumn('blog_informations', 'meta_description')) {
                    $table->text('meta_description')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('blog_informations')) {
            Schema::table('blog_informations', function (Blueprint $table) {
                if (Schema::hasColumn('blog_informations', 'meta_keywords')) {
                    $table->dropColumn('meta_keywords');
                }
                if (Schema::hasColumn('blog_informations', 'meta_description')) {
                    $table->dropColumn('meta_description');
                }
            });
        }
    }
};
