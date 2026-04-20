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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('phone')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->integer('whatsapp_status')->default(0);
                $table->string('password');
                $table->integer('status')->default(1);
                $table->integer('document_verified')->default(0);
                $table->double('avg_rating')->default(0);
                $table->integer('show_email_addresss')->default(1);
                $table->integer('show_phone_number')->default(1);
                $table->integer('show_contact_form')->default(1);
                $table->string('vendor_theme_version')->default('light');
                $table->string('code')->nullable();
                $table->string('lang_code')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('online_gateways')) {
            Schema::create('online_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('keyword')->nullable();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('online_gateways');
    }
};
