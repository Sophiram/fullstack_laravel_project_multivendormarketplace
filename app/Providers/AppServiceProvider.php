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
        View::composer('*', function ($view) {
            // យើងប្រើ Cache ដើម្បីកុំឱ្យវាហៅ DB គ្រប់ពេលដែលបើក Page
            $categories = Cache::remember('navbar_categories', 3600, function () {
                // យើងបន្ថែម try-catch ដើម្បីកុំឱ្យវាលោត Error បើ DB មិនទាន់មានតារាង
                try {
                    return \App\Models\Category::with('subcategories')->get();
                } catch (\Exception $e) {
                    return collect(); // បើមានបញ្ហា ឱ្យវាផ្ញើទិន្នន័យទទេជំនួសវិញ
                }
            });

            $view->with('navbarCategories', $categories);
        });
    }
}
