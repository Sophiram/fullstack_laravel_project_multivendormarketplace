<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first();

        $electronics = Category::where('category_name', 'Electronics')->first();
        $fashion = Category::where('category_name', 'Fashion & Apparel')->first();

        $smartphones = SubCategory::where('subcategory_name', 'Smartphones')->first();
        $laptops = SubCategory::where('subcategory_name', 'Laptops')->first();
        $shirts = SubCategory::where('subcategory_name', "Men's T-shirts")->first();

        if (!$store || !$electronics || !$fashion || !$smartphones || !$laptops || !$shirts) {
            $this->command->error('Missing seed data (store/category/subcategory)');
            return;
        }
        if (
            !$electronics ||
            !$fashion ||
            !$smartphones ||
            !$laptops ||
            !$shirts
        ) {
            $this->command->error(
                'Missing categories or subcategories. Run CategorySeeder and SubCategorySeeder first.'
            );
            return;
        }
        Product::insert([
            [
                'product_name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple smartphone with A17 Pro chip.',
                'sku' => 'IP15PRO001',
                'vendor_id' => $store->vendor_id,
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'store_id' => $store->id,
                'regular_price' => 1200,
                'discounted_price' => 1100,
                'tax_rate' => 10,
                'stock_quantity' => 50,
                'stock_status' => 'instock',
                'slug' => Str::slug('iPhone 15 Pro'),
                'visibility' => true,
                'meta_title' => 'iPhone 15 Pro',
                'meta_description' => 'Latest Apple smartphone.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_name' => 'Samsung Galaxy S25',
                'description' => 'Premium Android smartphone.',
                'sku' => 'SGS25001',
                'vendor_id' => $store->vendor_id,
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'store_id' => $store->id,
                'regular_price' => 999,
                'discounted_price' => 899,
                'tax_rate' => 10,
                'stock_quantity' => 40,
                'stock_status' => 'instock',
                'slug' => Str::slug('Samsung Galaxy S25'),
                'visibility' => true,
                'meta_title' => 'Samsung Galaxy S25',
                'meta_description' => 'Premium Android smartphone.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_name' => 'Dell XPS 13',
                'description' => 'Powerful ultrabook laptop.',
                'sku' => 'DELLXPS13001',
                'vendor_id' => $store->vendor_id,
                'category_id' => $electronics->id,
                'subcategory_id' => $laptops->id,
                'store_id' => $store->id,
                'regular_price' => 1500,
                'discounted_price' => 1400,
                'tax_rate' => 10,
                'stock_quantity' => 20,
                'stock_status' => 'instock',
                'slug' => Str::slug('Dell XPS 13'),
                'visibility' => true,
                'meta_title' => 'Dell XPS 13',
                'meta_description' => 'Premium laptop.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_name' => 'Men Premium T-Shirt',
                'description' => 'Comfortable cotton t-shirt.',
                'sku' => 'TSHIRT001',
                'vendor_id' => $store->vendor_id,
                'category_id' => $fashion->id,
                'subcategory_id' => $shirts->id,
                'store_id' => $store->id,
                'regular_price' => 25,
                'discounted_price' => 20,
                'tax_rate' => 5,
                'stock_quantity' => 100,
                'stock_status' => 'instock',
                'slug' => Str::slug('Men Premium T-Shirt'),
                'visibility' => true,
                'meta_title' => 'Men Premium T-Shirt',
                'meta_description' => 'Cotton t-shirt for men.',
                'status' => 'Published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
