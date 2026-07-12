<?php

namespace App\Providers;

use App\Models\backend\MenuPengaturan\ContactInfoModel;
use App\Models\backend\MenuPengaturan\FooterLinkModel;
use App\Models\medsos\Medsos;
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

        // Hanya di-load untuk footer (satu-satunya view yang memakai data ini),
        // dan di-cache agar tidak query di setiap request. Cache di-flush oleh
        // controller terkait (Medsos/ContactInfo/FooterLink) saat data berubah.
        View::composer('layouts.webromadan_frontend.fe_footer', function ($view) {
            $view->with('medsos', Cache::remember(
                'footer_medsos',
                now()->addHours(6),
                fn () => Medsos::orderBy('id', 'ASC')->take(5)->get()
            ));

            $view->with('contactInfo', Cache::remember(
                'footer_contact_info',
                now()->addHours(6),
                fn () => ContactInfoModel::first()
            ));

            $view->with('footerLinks', Cache::remember(
                'footer_links',
                now()->addHours(6),
                fn () => FooterLinkModel::where('is_active', true)->orderBy('sort_order')->get()
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
