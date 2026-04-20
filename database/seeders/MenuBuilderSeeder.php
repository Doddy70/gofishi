<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = '[{"text":"Home","href":"","icon":"empty","target":"_self","title":"","type":"home"},{"text":"Lokasi","href":"","icon":"empty","target":"_self","title":"","type":"hotels"},{"text":"Perahu","href":"","icon":"empty","target":"_self","title":"","type":"rooms"},{"text":"Vendors","href":"","icon":"empty","target":"_self","title":"","type":"vendors"},{"text":"Pricing","href":"","icon":"empty","target":"_self","title":"","type":"pricing"},{"text":"Blog","href":"","icon":"empty","target":"_self","title":"","type":"blog"},{"text":"FAQ","href":"","icon":"empty","target":"_self","title":"","type":"faq"},{"text":"Contact","href":"","icon":"empty","target":"_self","title":"","type":"contact"}]';
        
        \App\Models\MenuBuilder::updateOrCreate(
            ['language_id' => 1],
            ['menus' => $menus]
        );
    }
}
