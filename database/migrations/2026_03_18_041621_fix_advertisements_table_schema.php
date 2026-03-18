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
        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'ad_type')) {
                $table->string('ad_type')->after('id');
            }
            if (!Schema::hasColumn('advertisements', 'resolution_type')) {
                $table->smallInteger('resolution_type')->after('ad_type')->comment('1 => 300 x 250, 2 => 300 x 600, 3 => 728 x 90');
            }
            if (!Schema::hasColumn('advertisements', 'image')) {
                $table->string('image')->nullable()->after('resolution_type');
            }
            if (!Schema::hasColumn('advertisements', 'url')) {
                $table->string('url')->nullable()->after('image');
            }
            if (!Schema::hasColumn('advertisements', 'slot')) {
                $table->string('slot', 50)->nullable()->after('url');
            }
            if (!Schema::hasColumn('advertisements', 'views')) {
                $table->integer('views')->unsigned()->default(0)->after('slot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
             $table->dropColumn(['ad_type', 'resolution_type', 'image', 'url', 'slot', 'views']);
        });
    }
};
