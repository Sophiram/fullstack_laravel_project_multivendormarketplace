<?php

namespace App\Http\Controllers;

use App\Models\GiftCollection;
use App\Models\HomePageSetting;
use App\Models\Store;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        $homepagesetting = HomePageSetting::with([
            'discountedProduct.images',
            'featuredProduct1.images',
            'featuredProduct2.images'
        ])->first();
        $latestProducts = \App\Models\Product::with('images')
        ->where('status', 'Published')
        ->latest()
        ->take(8)
        ->get();
        return view('home.index', compact('homepagesetting', 'latestProducts'));
    }

    public function showCategoryProducts($category_name)
    {
        $category = \App\Models\Category::where('category_name', $category_name)->firstOrFail();

        $products = \App\Models\Product::where('category_id', $category->id)->get();

        // 1. ថែមទិន្នន័យ HomePageSetting ដូចទំព័រ Index ដែរ
        $homepagesetting = \App\Models\HomePageSetting::with([
            'discountedProduct.images',
            'featuredProduct1.images',
            'featuredProduct2.images'
        ])->first();

        // 2. បោះ variable $homepagesetting ទៅកាន់ view តាមរយៈ compact
        return view('home.category', compact('category', 'products', 'homepagesetting'));
    }
    public function showDiscounts()
    {
        // ទាញយក Discounts ទាំងអស់ដែលបាន Publish (status = 1)
        // លោកអ្នកអាចថែមលក្ខខណ្ឌ end_date >= ឥឡូវនេះ ឬ លក្ខខណ្ឌផ្សេងៗតាមការចង់បាន
        $discounts = \App\Models\Discount::where('status', 1)
            ->latest()
            ->get();

        // បោះ Variable $discounts ទៅកាន់ទំព័រ View (ឧទាហរណ៍៖ home.discount)
        return view('home.discount', compact('discounts'));
    }

    public function showGiftCollections()
    {
        // ទាញយក Gift Sets ណាដែលសកម្ម (status = true)
        $giftCollections = GiftCollection::where('status', true)->latest()->get();

        // សន្មតថាអ្នកទុក File ជំហានមុននៅ resources/views/home/gift-collection.blade.php
        return view('home.gift-collection', compact('giftCollections'));
    }

    /**
     * 🔍 ២. បង្ហាញព័ត៌មានលម្អិតនៃ Gift Box នីមួយៗ (View Detail Page)
     */
    public function showGiftDetail($id)
    {
        $gift = GiftCollection::where('status', true)->findOrFail($id);

        return view('home.gift-detail', compact('gift'));
    }

    public function showStores()
    {
        // ទាញយកហាងណាដែលបាន Approved និងបើកដំណើរការ (is_active = 1)
        $stores = Store::where('status', 'approved')
            ->where('is_active', 1)
            ->latest()
            ->get();

        return view('home.store', compact('stores'));
    }

   public function storeDetails($slug)
    {
        // 🔍 ទាញយកទិន្នន័យហាង ព្រមទាំងផលិតផលណាដែលបាន "Published" ដោយ Admin
        $store = \App\Models\Store::with(['products' => function($query) {
                $query->where('status', 'Published'); // ✅ ប្រើប្រាស់ 'Published' តាម Table products ជាក់ស្តែង
            }])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', 1)
            ->firstOrFail();

        return view('home.store_details', compact('store'));
    }

}
