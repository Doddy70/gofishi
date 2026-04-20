<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'photo')) {
                $table->string('photo')->nullable()->after('username');
            }
            if (!Schema::hasColumn('vendors', 'fname')) {
                $table->string('fname')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('vendors', 'lname')) {
                $table->string('lname')->nullable()->after('fname');
            }
            if (!Schema::hasColumn('vendors', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('vendors', 'zip_code')) {
                $table->string('zip_code')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['photo', 'fname', 'lname', 'address', 'state', 'city', 'zip_code']);
        });
    }
};
