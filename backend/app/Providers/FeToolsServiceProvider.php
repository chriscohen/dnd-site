<?php

namespace App\Providers;

use App\Services\FeToolsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FeToolsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FeToolsService::class, function (Application $app) {
            return new FeToolsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Allow deferral of service registration.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [FeToolsService::class];
    }
}
