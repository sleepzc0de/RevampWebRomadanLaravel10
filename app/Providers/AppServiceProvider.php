<?php

namespace App\Providers;

use App\Models\medsos\medsos;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::defaultView('pagination.bootstrap-5');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Paginator::useBootstrapRomadan();




        View::share('medsos', medsos::orderBy("id", "ASC")->take(5)->get());
    }
}
