<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTierSeeder extends Seeder
{
    public function run()
    {
        // Define standard modules for permissions
        $all_permissions = [
            "Menu Builder", "Package Management", "Lokasi Management", "Perahu Management", 
            "Perahu Bookings", "Payment Log", "Featured Lokasi", "Featured Perahu", 
            "Lokasi Specifications", "Shop Management", "User Management", "Vendors Management", 
            "Transaction", "Home Page", "Support Tickets", "Footer", "Custom Pages", 
            "Blog Management", "FAQ Management", "Advertisements", "Announcement Popups", 
            "Withdrawals Management", "Payment Gateways", "Basic Settings", "Admin Management", 
            "Language Management"
        ];

        $manager_permissions = [
            "Lokasi Management", "Perahu Management", "Perahu Bookings", "Payment Log", 
            "User Management", "Vendors Management", "Transaction", "Support Tickets", 
            "Blog Management", "FAQ Management", "Withdrawals Management"
        ];

        $staff_permissions = [
            "Perahu Bookings", "Support Tickets", "FAQ Management", "User Management"
        ];

        // 1. Super Admin (Admin 1)
        DB::table('role_permissions')->updateOrInsert(
            ['name' => 'Admin 1 (Super Admin)'],
            ['permissions' => json_encode($all_permissions), 'updated_at' => now()]
        );

        // 2. Manager (Admin 2)
        DB::table('role_permissions')->updateOrInsert(
            ['name' => 'Admin 2 (Manager)'],
            ['permissions' => json_encode($manager_permissions), 'updated_at' => now()]
        );

        // 3. Staff (Admin 3)
        DB::table('role_permissions')->updateOrInsert(
            ['name' => 'Admin 3 (Staff)'],
            ['permissions' => json_encode($staff_permissions), 'updated_at' => now()]
        );

        $this->command->info('Admin Tiers (1, 2, 3) Seeded Successfully!');
    }
}
