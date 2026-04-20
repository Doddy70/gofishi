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
            $cols = [
                'maintenance_img' => 'string,255,nullable',
                'maintenance_msg' => 'text,nullable',
                'bypass_token' => 'string,255,nullable',
                'footer_logo' => 'string,255,nullable',
                'footer_background_image' => 'string,255,nullable',
                'contact_title' => 'string,255,nullable',
                'contact_subtile' => 'string,255,nullable',
                'contact_details' => 'longText,nullable',
                'latitude' => 'string,255,nullable',
                'longitude' => 'string,255,nullable',
                'about_section_image' => 'string,255,nullable',
                'hero_section_image' => 'string,255,nullable',
                'feature_section_image' => 'string,255,nullable',
                'counter_section_image' => 'string,255,nullable',
                'call_to_action_section_image' => 'string,255,nullable',
                'call_to_action_section_inner_image' => 'string,255,nullable',
                'testimonial_section_image' => 'string,255,nullable',
                'admin_approval_notice' => 'text,nullable',
                'self_pickup_status' => 'tinyInteger,nullable',
                'two_way_delivery_status' => 'tinyInteger,nullable',
                'equipment_tax_amount' => 'decimal,5,2,nullable',
            ];

            foreach ($cols as $col => $typeStr) {
                if (!Schema::hasColumn('basic_settings', $col)) {
                    $parts = explode(',', $typeStr);
                    $type = $parts[0];
                    if ($type == 'string') {
                        $table->string($col, (int)$parts[1])->nullable();
                    } elseif ($type == 'text') {
                        $table->text($col)->nullable();
                    } elseif ($type == 'longText') {
                        $table->longText($col)->nullable();
                    } elseif ($type == 'tinyInteger') {
                        $table->unsignedTinyInteger($col)->nullable();
                    } elseif ($type == 'decimal') {
                        $table->decimal($col, (int)$parts[1], (int)$parts[2])->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            // Drop them if needed, but usually we just keep them at this stage.
        });
    }
};
