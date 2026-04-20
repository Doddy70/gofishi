<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Boat Specifications
            $table->integer('crew_count')->default(0)->after('boat_width');
            $table->string('engine_1')->nullable()->after('crew_count');
            $table->string('engine_2')->nullable()->after('engine_1');
            
            // Fishing & Safety Features
            $table->boolean('bait')->default(false)->after('engine_2');
            $table->boolean('fishing_gear')->default(false)->after('bait');
            $table->boolean('life_jacket')->default(false)->after('fishing_gear');
            
            // Catering
            $table->boolean('breakfast')->default(false)->after('life_jacket');
            $table->boolean('lunch')->default(false)->after('breakfast');
            $table->boolean('dinner')->default(false)->after('lunch');
            $table->boolean('mineral_water')->default(false)->after('dinner');
            
            // Onboard Amenities
            $table->boolean('ac')->default(false)->after('mineral_water');
            $table->boolean('wifi')->default(false)->after('ac');
            $table->boolean('electricity')->default(false)->after('wifi');
            $table->boolean('stove')->default(false)->after('electricity');
            $table->boolean('refrigerator')->default(false)->after('stove');
            $table->text('other_features')->nullable()->after('refrigerator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'crew_count', 'engine_1', 'engine_2', 'bait', 'fishing_gear', 
                'life_jacket', 'breakfast', 'lunch', 'dinner', 'mineral_water',
                'ac', 'wifi', 'electricity', 'stove', 'refrigerator', 'other_features'
            ]);
        });
    }
};
