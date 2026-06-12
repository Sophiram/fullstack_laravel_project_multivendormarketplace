<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            ['subcategory_name' => 'Smartphones', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Laptops', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Cameras', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Headphones', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Smart Watches', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['subcategory_name' => "Men's T-shirts", 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => "Women's Dresses", 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Shoes', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Bags', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Accessories', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            ['subcategory_name' => 'Living Room Furniture', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Kitchenware', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Lighting', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Decorations', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            ['subcategory_name' => 'Skincare', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Makeup', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Hair Care', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Perfumes', 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            ['subcategory_name' => 'Tools & Equipment', 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['subcategory_name' => 'Skiing', 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],

            ['subcategory_name' => 'Fruit', 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('sub_categories')->insert($subCategories);
    }
}
