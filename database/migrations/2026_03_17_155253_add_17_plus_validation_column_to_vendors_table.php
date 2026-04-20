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
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('identity_document')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('identity_document');
            $table->boolean('is_verified_17_plus')->default(0)->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['identity_document', 'date_of_birth', 'is_verified_17_plus']);
        });
    }
};
