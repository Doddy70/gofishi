<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyVendorSeeder extends Seeder
{
    public function run()
    {
        $vendors = [
            [
                'username' => 'juragan_laut',
                'email' => 'juragan@gofishi.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
                'phone' => '081234567890',
                'whatsapp_number' => '081234567890',
            ],
            [
                'username' => 'capt_jack',
                'email' => 'capt@gofishi.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
                'phone' => '081234567891',
                'whatsapp_number' => '081234567891',
            ],
        ];

        foreach ($vendors as $vendorData) {
            Vendor::updateOrCreate(['username' => $vendorData['username']], $vendorData);
        }
    }
}
