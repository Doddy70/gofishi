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
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->text('permissions')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('role_id')->nullable();
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('image')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('admins');
    }
};
