<?php

namespace App\Providers;

use App\Contracts\ExternalApiServiceInterface;
use App\Services\ZohoApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExternalApiServiceInterface::class, ZohoApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
