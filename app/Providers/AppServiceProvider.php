<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. เพิ่ม use statement นี้

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
        Paginator::useBootstrapFive(); // 2. เพิ่มบรรทัดนี้

        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }

    }
}
