<?php

namespace App\Providers;

use App\Models\medsos\medsos;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination.bootstrap-5');

        // Hanya di-load untuk footer (satu-satunya view yang memakai $medsos),
        // dan di-cache agar tidak query di setiap request.
        View::composer('layouts.webromadan_frontend.fe_footer', function ($view) {
            $view->with('medsos', Cache::remember(
                'footer_medsos',
                now()->addHours(6),
                fn () => medsos::orderBy('id', 'ASC')->take(5)->get()
            ));
        });

        // Jalankan storage:link jika symbolic link belum ada
        if (! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
