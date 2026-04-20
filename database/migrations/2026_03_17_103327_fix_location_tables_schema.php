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
        // Fix Countries Table
        if (Schema::hasTable('countries')) {
            Schema::table('countries', function (Blueprint $table) {
                if (!Schema::hasColumn('countries', 'language_id')) {
                    $table->bigInteger('language_id')->unsigned()->nullable()->after('id');
                }
            });
        }

        // Fix Cities Table
        if (Schema::hasTable('cities')) {
            Schema::table('cities', function (Blueprint $table) {
                if (!Schema::hasColumn('cities', 'country_id')) {
                    $table->bigInteger('country_id')->unsigned()->nullable()->after('language_id');
                }
                if (!Schema::hasColumn('cities', 'state_id')) {
                    $table->bigInteger('state_id')->unsigned()->nullable()->after('country_id');
                }
                if (!Schema::hasColumn('cities', 'feature_image')) {
                    $table->string('feature_image')->nullable()->after('state_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('language_id');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'state_id', 'feature_image']);
        });
    }
};
