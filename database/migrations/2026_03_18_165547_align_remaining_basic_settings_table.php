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
            if (!Schema::hasColumn('basic_settings', 'logo_two')) {
                $table->string('logo_two', 255)->nullable()->after('logo');
            }
            if (!Schema::hasColumn('basic_settings', 'disqus_status')) {
                $table->unsignedTinyInteger('disqus_status')->default(0)->after('breadcrumb');
            }
            if (!Schema::hasColumn('basic_settings', 'disqus_short_name')) {
                $table->string('disqus_short_name', 255)->nullable()->after('disqus_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_two', 'disqus_status', 'disqus_short_name']);
        });
    }
};
