<?php

namespace App\Services\AccessApi;

use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AccessClient::class, function ($app) {
            return new AccessClient();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../../config/access.php' => config_path('access.php'),
        ], 'access-config');
    }
}