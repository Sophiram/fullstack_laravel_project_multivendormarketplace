<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin123@email.com'],
            [
                
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 0,
            ]
        );

        User::firstOrCreate(
            ['email' => 'vendor123@example.com'],
            [
                'name' => 'Vendor User',
                'password' => Hash::make('vendor123'),
                'role' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'role' => 2,
            ]
        );

        $this->call([
            CategorySeeder::class,
            SubCategorySeeder::class,

            VendorSeeder::class,
            StoreSeeder::class,

            ProductSeeder::class,
            ProductImageSeeder::class,
            HomePageSettingSeeder::class,
        ]);
    }
}
