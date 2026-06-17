<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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
        // ១. បង្ខំឱ្យ Laravel ប្រើ https ជានិច្ចនៅលើ Production (ដោះស្រាយបញ្ហាបាត់ 's' ពេល Login)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // ២. ប្ដូរ Driver ទៅជា 'array' ដើម្បីកុំឱ្យវាប្រើ Database សម្រាប់ Cache ពេលដែល DB មានបញ្ហា
        config(['cache.default' => 'array']);

        // ៣. ផ្ទុកទិន្នន័យ Category សម្រាប់បង្ហាញនៅលើ Navbar គ្រប់ទំព័រ
        View::composer('*', function ($view) {
            $categories = Cache::remember('navbar_categories', 3600, function () {
                try {
                    return Category::with('subcategories')->get();
                } catch (\Exception $e) {
                    return collect();
                }
            });

            $view->with('navbarCategories', $categories);
        });
    }
}
