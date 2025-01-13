<?php

namespace App\Providers;

use App\Services\BackupService;
use Illuminate\Support\ServiceProvider;

    class BackupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(BackupService::class, function ($app) {
            return new BackupService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
