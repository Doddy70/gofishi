<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyGoFishiSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DummyVendorSeeder::class,
            DummyLocationSeeder::class,
            DummyBoatSeeder::class,
            DummyArticleSeeder::class,
        ]);
    }
}
