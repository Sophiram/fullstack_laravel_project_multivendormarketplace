<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class HomePageSettingSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::take(3)->get();

        if ($products->count() < 3) {
            $this->command->error('Need at least 3 products before seeding home_page_settings.');
            return;
        }

        DB::table('home_page_settings')->updateOrInsert(
            ['id' => 1],
            [
                'discounted_product_id' => $products[0]->id,
                'discount_percent' => 20.00,
                'discount_heading' => 'Special Summer Sale',
                'discount_subheading' => 'Get amazing discounts on selected products.',
                'featured_product_1_id' => $products[1]->id,
                'featured_product_2_id' => $products[2]->id,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
