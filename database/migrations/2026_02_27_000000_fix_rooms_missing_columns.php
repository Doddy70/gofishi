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
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'perahu_area_text')) {
                $table->string('perahu_area_text')->nullable()->after('area');
            }
            if (!Schema::hasColumn('rooms', 'daily_price')) {
                $table->decimal('daily_price', 10, 2)->default(0)->after('perahu_area_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('perahu_area_text');
        });
    }
};
