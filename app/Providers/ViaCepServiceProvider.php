<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ViaCepService;

class ViaCepServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ViaCepService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
