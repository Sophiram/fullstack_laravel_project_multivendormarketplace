<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            ['name' => 'Smartphones', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptops', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cameras', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headphones', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smart Watches', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Men\'s T-shirts', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Women\'s Dresses', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shoes', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bags', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accessories', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Living Room Furniture', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchenware', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lighting', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Decorations', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Skincare', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Makeup', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Care', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perfumes', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tools & Equipment', 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fruilt', 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Skiing', 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('sub_categories')->insert($subCategories);
    }
}
