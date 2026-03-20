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
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'language_id')) {
                $table->unsignedBigInteger('language_id')->after('id');
            }
            if (!Schema::hasColumn('faqs', 'question')) {
                $table->string('question')->after('language_id');
            }
            if (!Schema::hasColumn('faqs', 'answer')) {
                $table->text('answer')->after('question');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['language_id', 'question', 'answer']);
        });
    }
};
