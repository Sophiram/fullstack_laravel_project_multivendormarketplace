<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fashion & Apparel', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Home & Furniture', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beauty & Personal Care', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports & Outdoors', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Groceries', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
