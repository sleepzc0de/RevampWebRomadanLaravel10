<?php

namespace App\Providers;

use App\Models\medsos\medsos;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/VersionHelper.php');
        Paginator::defaultView('pagination.bootstrap-5');
        View::composer('*', function ($view) {
            $view->with('medsos', medsos::orderBy("id", "ASC")->take(5)->get());
        });
    }

    public function boot(): void
    {
         // Jalankan storage:link jika symbolic link belum ada
         if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }


    }
}
