<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

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
        // ប្ដូរ Driver ទៅជា 'array' ដើម្បីកុំឱ្យវាប្រើ Database សម្រាប់ Cache ពេលដែល DB មានបញ្ហា
        config(['cache.default' => 'array']);

        View::composer('*', function ($view) {
            $categories = Cache::remember('navbar_categories', 3600, function () {
                try {
                    return \App\Models\Category::with('subcategories')->get();
                } catch (\Exception $e) {
                    return collect();
                }
            });

            $view->with('navbarCategories', $categories);
        });
    }
}
