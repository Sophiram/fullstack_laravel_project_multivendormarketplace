<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Fashion & Apparel', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Home & Furniture', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Beauty & Personal Care', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Sports & Outdoors', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Groceries', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
