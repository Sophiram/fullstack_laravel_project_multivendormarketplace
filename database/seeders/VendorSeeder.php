<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorUser = User::where(
            'email',
            'vendor123@example.com'
        )->first();

        if (!$vendorUser) {
            return;
        }

        DB::table('vendors')->insert([
            [
                'user_id' => $vendorUser->id,
                'commission_rate' => 10.00,
                'approval_status' => 'approved',
                'bank_account_info' => 'ABA Bank - 123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
