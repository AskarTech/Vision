<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Models\SellerManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@yemenwifi.com',
            'phone' => '+967700000001',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Seller
        $seller = Seller::create([
            'name' => 'Test Seller',
            'slug' => 'test-seller',
            'phone' => '+967700000002',
            'status' => 'active',
            'commission_rate' => 10,
        ]);

        // Create Seller Manager User
        User::create([
            'name' => 'Seller Manager',
            'email' => 'manager@yemenwifi.com',
            'phone' => '+967700000003',
            'password' => Hash::make('password'),
            'role' => 'seller_manager',
            'seller_id' => $seller->id,
        ]);

        // Create regular user
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone' => '+967700000004',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
