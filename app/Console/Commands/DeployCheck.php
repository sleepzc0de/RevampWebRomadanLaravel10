<?php

namespace App\Console\Commands;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Frontend\HomeFeController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

/**
 * Pemeriksaan kesiapan aplikasi setelah deploy.
 *
 * Dijalankan oleh deploy.sh SEBELUM `php artisan up`, sehingga kegagalan
 * langkah deploy (mis. `npm run build` tidak jalan) ketahuan saat itu juga
 * dan situs tetap di halaman pemeliharaan — bukan menampilkan 500 ke publik.
 */
class DeployCheck extends Command
{
    protected $signature = 'deploy:check {--skip-db : Lewati pemeriksaan database}';

    protected $description = 'Verifikasi kesiapan aplikasi setelah deploy (aset build, vendor, izin tulis, database)';

    /** Entry Vite yang wajib ada di manifest — harus sinkron dengan vite.config.js */
    private const VITE_ENTRIES = [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/backend.css',
        'resources/js/backend.js',
    ];

    private int $failed = 0;

    private int $warned = 0;

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <options=bold>Pemeriksaan Kesiapan Deploy — CMS Romadan</>');

        $this->section('Konfigurasi aplikasi');
        $this->checkAppKey();
        $this->checkAppEnv();
        $this->checkAppDebug();
        $this->checkLogChannel();

        $this->section('Aset frontend (hasil build Vite)');
        $this->checkViteManifest();

        $this->section('Dependensi Composer');
        $this->checkVendorApi();

        $this->section('Izin tulis & symlink');
        $this->checkWritablePaths();
        $this->checkStorageLink();

        if (! $this->option('skip-db')) {
            $this->section('Database');
            $this->checkDatabase();
        }

        $this->section('Uji render halaman (simulasi pengunjung)');
        $this->checkSmokeRender();

        return $this->summary();
    }

    // ===================== Pemeriksaan =====================

    private function checkAppKey(): void
    {
        $key = config('app.key');
        $key
            ? $this->resultOk('APP_KEY', 'terisi')
            : $this->resultFail('APP_KEY', 'kosong', 'php artisan key:generate');
    }

    private function checkAppEnv(): void
    {
        $env = config('app.env');
        $env === 'production'
            ? $this->resultOk('APP_ENV', $env)
            : $this->resultWarn('APP_ENV', "bernilai '{$env}', seharusnya 'production'", 'ubah APP_ENV=production di .env');
    }

    private function checkAppDebug(): void
    {
        $debug = config('app.debug');
        $produksi = config('app.env') === 'production';

        if (! $debug) {
            $this->resultOk('APP_DEBUG', 'nonaktif');

            return;
        }

        // APP_DEBUG=true wajar (bahkan diperlukan) di lingkungan pengembangan;
        // hanya berbahaya bila lingkungannya production.
        $produksi
            ? $this->resultFail('APP_DEBUG', 'aktif di production — detail error bocor ke publik', 'ubah APP_DEBUG=false di .env')
            : $this->resultOk('APP_DEBUG', "aktif (wajar untuk APP_ENV=".config('app.env').')');
    }

    private function checkViteManifest(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            $this->resultFail(
                'Manifest Vite',
                'public/build/manifest.json TIDAK ADA — seluruh halaman akan 500',
                'npm ci --include=dev && npm run build'
            );

            return;
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        if (! is_array($manifest)) {
            $this->resultFail('Manifest Vite', 'isi manifest.json rusak / bukan JSON', 'npm run build');

            return;
        }

        $missingEntries = [];
        $missingFiles = [];
        foreach (self::VITE_ENTRIES as $entry) {
            if (! isset($manifest[$entry]['file'])) {
                $missingEntries[] = $entry;

                continue;
            }
            if (! file_exists(public_path('build/'.$manifest[$entry]['file']))) {
                $missingFiles[] = $manifest[$entry]['file'];
            }
        }

        if ($missingEntries) {
            $this->resultFail('Entry Vite', 'tidak ada di manifest: '.implode(', ', $missingEntries), 'npm run build');
        } elseif ($missingFiles) {
            $this->resultFail('Berkas aset', 'tercatat di manifest tapi hilang: '.implode(', ', $missingFiles), 'npm run build');
        } else {
            $this->resultOk('Manifest Vite', count(self::VITE_ENTRIES).' entry lengkap, berkas aset tersedia');
        }
    }

    /**
     * Cegah kondisi "vendor tidak sinkron dengan kode": composer.lock sudah
     * di-pull tapi `composer install` belum dijalankan, sehingga versi paket
     * di vendor/ berbeda dengan API yang dipakai kode (fatal saat runtime).
     */
    private function checkVendorApi(): void
    {
        trait_exists(LogsActivity::class)
            ? $this->resultOk('spatie/laravel-activitylog', 'API cocok dengan kode (Traits\LogsActivity)')
            : $this->resultFail(
                'spatie/laravel-activitylog',
                'versi vendor tidak cocok dengan kode — pencatatan aktivitas akan fatal',
                'composer install --no-dev --optimize-autoloader'
            );

        class_exists(Role::class)
            ? $this->resultOk('spatie/laravel-permission', 'model Role tersedia')
            : $this->resultFail('spatie/laravel-permission', 'model Role tidak ditemukan', 'composer install --no-dev --optimize-autoloader');

        class_exists(Google2FA::class)
            ? $this->resultOk('pragmarx/google2fa', 'tersedia (2FA)')
            : $this->resultFail('pragmarx/google2fa', 'tidak ditemukan', 'composer install --no-dev --optimize-autoloader');
    }

    /**
     * Render halaman publik yang paling kritis persis seperti saat diakses
     * pengunjung, lalu tangkap exception-nya. Ini menangkap kelas kegagalan
     * yang tidak terlihat dari pemeriksaan berkas — manifest Vite hilang,
     * trait vendor tak cocok, kolom database kurang, view rusak — dan
     * menampilkan pesan aslinya walaupun APP_DEBUG=false.
     */
    private function checkSmokeRender(): void
    {
        $pages = [
            'Halaman login' => fn () => app(AuthController::class)->showLoginForm(),
            'Halaman depan' => fn () => app(HomeFeController::class)->index(),
        ];

        foreach ($pages as $label => $render) {
            try {
                $request = Request::create('/', 'GET');
                $request->setLaravelSession(app('session')->driver('array'));
                app()->instance('request', $request);
                View::share('errors', new ViewErrorBag);

                $html = $render()->render();

                strlen($html) > 500
                    ? $this->resultOk($label, number_format(strlen($html)).' bytes ter-render')
                    : $this->resultFail($label, 'hasil render mencurigakan (hanya '.strlen($html).' bytes)', 'periksa view terkait');
            } catch (\Throwable $e) {
                $this->resultFail(
                    $label,
                    class_basename($e).': '.$e->getMessage(),
                    'sumber: '.basename($e->getFile()).':'.$e->getLine()
                );
            }
        }
    }

    private function checkLogChannel(): void
    {
        $channel = config('logging.default');

        // Terukur: `LOG_CHANNEL=null` di .env dibaca env() sebagai PHP null
        // (bukan channel bernama "null"), sehingga channel default tidak valid.
        // Akibatnya log TIDAK dibuang — setiap penulisan memicu exception lalu
        // ditulis lewat emergency logger lengkap dengan stack trace (~1,4 KB
        // per baris). Jadi setelan ini justru lebih boros daripada logging biasa.
        if ($channel === null || $channel === 'null') {
            $this->resultWarn(
                'LOG_CHANNEL',
                'tidak valid — log tetap ditulis via emergency logger (~1,4 KB/baris, lebih boros)',
                'jalankan setup-server.sh, atau setel LOG_CHANNEL=daily & LOG_LEVEL=error di .env'
            );

            return;
        }

        $this->resultOk('LOG_CHANNEL', $channel);
    }

    private function checkWritablePaths(): void
    {
        $paths = [
            'storage/logs' => storage_path('logs'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $notWritable = [];
        foreach ($paths as $label => $path) {
            if (! is_dir($path) || ! is_writable($path)) {
                $notWritable[] = $label;
            }
        }

        $notWritable
            ? $this->resultFail(
                'Izin tulis',
                'tidak bisa ditulis: '.implode(', ', $notWritable),
                'sudo chown -R www-data:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache'
            )
            : $this->resultOk('Izin tulis', count($paths).' direktori writable');
    }

    private function checkStorageLink(): void
    {
        file_exists(public_path('storage'))
            ? $this->resultOk('Symlink storage', 'public/storage tersedia')
            : $this->resultFail('Symlink storage', 'public/storage tidak ada — gambar tidak akan tampil', 'php artisan storage:link');
    }

    private function checkDatabase(): void
    {
        try {
            DB::connection()->getPdo();
            $this->resultOk('Koneksi database', config('database.default').' → '.DB::connection()->getDatabaseName());
        } catch (\Throwable $e) {
            $this->resultFail('Koneksi database', 'gagal terhubung: '.$e->getMessage(), 'periksa DB_* di .env');

            return;
        }

        try {
            $ran = DB::table('migrations')->pluck('migration')->all();
            $files = collect(glob(database_path('migrations/*.php')))
                ->map(fn ($p) => basename($p, '.php'))
                ->all();
            $pending = array_diff($files, $ran);

            $pending
                ? $this->resultFail('Migrasi', count($pending).' migrasi belum dijalankan', 'php artisan migrate --force')
                : $this->resultOk('Migrasi', 'semua migrasi sudah dijalankan');
        } catch (\Throwable $e) {
            $this->resultFail('Migrasi', 'status tidak dapat diperiksa: '.$e->getMessage(), 'php artisan migrate --force');
        }
    }

    // ===================== Tampilan =====================

    private function section(string $title): void
    {
        $this->newLine();
        $this->line('  <fg=gray>'.$title.'</>');
    }

    private function resultOk(string $label, string $detail): void
    {
        $this->line(sprintf('  <fg=green>OK   </> %s <fg=gray>%s</>', str_pad($label, 28), $detail));
    }

    private function resultWarn(string $label, string $detail, string $fix): void
    {
        $this->warned++;
        $this->line(sprintf('  <fg=yellow>PERIKSA</> %s <fg=yellow>%s</>', str_pad($label, 26), $detail));
        $this->line(sprintf('  %s<fg=gray>perbaiki: %s</>', str_repeat(' ', 8), $fix));
    }

    private function resultFail(string $label, string $detail, string $fix): void
    {
        $this->failed++;
        $this->line(sprintf('  <fg=red;options=bold>GAGAL</> %s <fg=red>%s</>', str_pad($label, 28), $detail));
        $this->line(sprintf('  %s<fg=gray>perbaiki: %s</>', str_repeat(' ', 8), $fix));
    }

    private function summary(): int
    {
        $this->newLine();

        if ($this->failed > 0) {
            $this->line('  <bg=red;fg=white;options=bold> TIDAK SIAP </> '.$this->failed.' pemeriksaan gagal — jangan jalankan `php artisan up` sebelum diperbaiki.');
            $this->newLine();

            return self::FAILURE;
        }

        $this->line('  <bg=green;fg=white;options=bold> SIAP </> Semua pemeriksaan lolos'
            .($this->warned > 0 ? ' ('.$this->warned.' perlu diperiksa).' : '.'));
        $this->newLine();

        return self::SUCCESS;
    }
}
