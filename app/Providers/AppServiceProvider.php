<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        // ប្រើ Cache ដើម្បីការពារកុំឱ្យហៅ Database រាល់ពេលផ្ទុកទំព័រ
        View::composer('*', function ($view) {
            $categories = \Illuminate\Support\Facades\Cache::remember('navbar_categories', 3600, function () {
                // ប្រើ try-catch ដើម្បីការពារករណី Database មានបញ្ហា វានឹងមិនធ្វើឱ្យវេបសាយគាំងឡើយ
                try {
                    return \App\Models\Category::with('subcategories')->get();
                } catch (\Exception $e) {
                    return collect(); // បើទាញមិនបាន ឱ្យវាត្រលប់មកទទេវិញ
                }
            });

            $view->with('navbarCategories', $categories);
        });
    }
}
