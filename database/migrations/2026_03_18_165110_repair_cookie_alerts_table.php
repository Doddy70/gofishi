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
        Schema::table('cookie_alerts', function (Blueprint $table) {
            if (!Schema::hasColumn('cookie_alerts', 'language_id')) {
                $table->unsignedBigInteger('language_id')->after('id');
            }
            if (!Schema::hasColumn('cookie_alerts', 'cookie_alert_status')) {
                $table->unsignedTinyInteger('cookie_alert_status')->after('language_id')->default(0);
            }
            if (!Schema::hasColumn('cookie_alerts', 'cookie_alert_btn_text')) {
                $table->string('cookie_alert_btn_text')->after('cookie_alert_status')->nullable();
            }
            if (!Schema::hasColumn('cookie_alerts', 'cookie_alert_text')) {
                $table->text('cookie_alert_text')->after('cookie_alert_btn_text')->nullable();
            }
        });

        // Seed default data if empty
        if (\DB::table('cookie_alerts')->count() == 0) {
            $langs = \DB::table('languages')->get();
            foreach ($langs as $lang) {
                \DB::table('cookie_alerts')->insert([
                    'language_id' => $lang->id,
                    'cookie_alert_status' => 0,
                    'cookie_alert_btn_text' => 'I Agree',
                    'cookie_alert_text' => 'We use cookies to give you the best online experience.',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cookie_alerts', function (Blueprint $table) {
            $table->dropColumn(['language_id', 'cookie_alert_status', 'cookie_alert_btn_text', 'cookie_alert_text']);
        });
    }
};
