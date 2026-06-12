<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = DB::table('vendors')->first();

        if (!$vendor) {
            return;
        }

        DB::table('stores')->insert([
            [
                'vendor_id' => $vendor->id,
                'store_name' => 'Tech Paradise',
                'slug' => Str::slug('Tech Paradise'),
                'details' => 'Electronics and gadgets',
                'address' => 'Phnom Penh',
                'store_email' => 'tech@example.com',
                'store_phone' => '012345678',
                'status' => 'approved',
                'commission_rate' => 10,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
