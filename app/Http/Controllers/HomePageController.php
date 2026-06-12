<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount; 
use App\Models\GiftCollection;
use App\Models\HomePageSetting;
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
        $category = Category::where('category_name', $category_name)->firstOrFail();

        $products = Product::with('images')->where('category_id', $category->id)->get();

        $homepagesetting = \App\Models\HomePageSetting::with([
            'discountedProduct.images',
            'featuredProduct1.images',
            'featuredProduct2.images'
        ])->first();

        return view('home.category', compact('category', 'products', 'homepagesetting'));
    }
    public function showDiscounts()
    {
        $discounts = Discount::where('status', 1)
            ->latest()
            ->get();

        return view('home.discount', compact('discounts'));
    }
    public function showGiftCollections()
    {
        $giftCollections = GiftCollection::where('status', true)->latest()->get();
        return view('home.gift-collection', compact('giftCollections'));
    }
    public function showGiftDetail($id)
    {
        $gift = GiftCollection::where('status', true)->findOrFail($id);

        return view('home.gift-detail', compact('gift'));
    }
    public function showStores()
    {
        $stores = Store::where('status', 'approved')
            ->where('is_active', 1)
            ->latest()
            ->get();

        return view('home.store', compact('stores'));
    }

   public function storeDetails($slug)
    {
        $store = Store::with(['products' => function($query) {
                $query->where('status', 'Published');
            }])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', 1)
            ->firstOrFail();

        return view('home.store_details', compact('store'));
    }
    public function showAboutUs()
    {
        return view('home.about-us');
    }
    public function showDeliveryInfo()
    {
        return view('home.delivery-info');
    }
    public function showPrivacyPolicy()
    {
        return view('home.privacy-policy');
    }
    public function showTermsConditions()
    {
        return view('home.terms-conditions');
    }

}
