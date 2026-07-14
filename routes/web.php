<?php

use App\Http\Controllers\ActivityLog\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\HomeBeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Frontend\HomeFeController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Medsos\MedsosController;
use App\Http\Controllers\MenuFAQ\FAQController;
use App\Http\Controllers\MenuInformasiPublik\AplikasiController;
use App\Http\Controllers\MenuInformasiPublik\InformasiPublikController;
use App\Http\Controllers\MenuInformasiPublik\PedomanController;
use App\Http\Controllers\MenuInformasiPublik\PeraturanController;
use App\Http\Controllers\MenuKegiatan\KegiatanController;
use App\Http\Controllers\MenuLayanan\LayananController;
use App\Http\Controllers\MenuMedia\MediaBlobController;
use App\Http\Controllers\MenuMedia\MediaController;
use App\Http\Controllers\MenuPengaturan\ContactInfoController;
use App\Http\Controllers\MenuPengaturan\FooterLinkController;
use App\Http\Controllers\MenuProfile\SejarahController;
use App\Http\Controllers\MenuProfile\StrukturJabatanController;
use App\Http\Controllers\MenuProfile\TentangController;
use App\Http\Controllers\MenuProfile\VisiMisiController;
use App\Http\Controllers\MenuPublikasi\PublikasiController;
use App\Http\Controllers\MenuVisitor\VisitorController;
use App\Http\Controllers\Referensi\RefJenisPeraturanController;
use App\Http\Controllers\Referensi\RefKategoriController;
use App\Http\Controllers\Referensi\RefPeraturanStatusController;
use App\Http\Controllers\Referensi\RefStatusController;
use App\Http\Controllers\Referensi\RefTipeController;
use App\Http\Controllers\Security\TwoFactorController;
use App\Http\Controllers\SystemMonitorController;
use App\Http\Controllers\Tim\PengembangController;
use App\Http\Controllers\UserManajemen\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 1. FRONT END
Route::group(
    ['prefix' => '/', 'middleware' => ['log.visitor']],
    function () {

        // HOME
        Route::get('/', [HomeFeController::class, 'index'])->name('homefe');

        // PENCARIAN GLOBAL
        Route::get('/cari', [HomeFeController::class, 'globalSearch'])->name('search-fe');

        // MENU PROFILE
        Route::prefix('/profile')->group(function () {
            // VISI DAN MISI
            Route::get('/visi-misi', [HomeFeController::class, 'profile_visi_misi'])->name('visi-misi-fe');
            // SEJARAH
            Route::get('/sejarah', [HomeFeController::class, 'profile_sejarah'])->name('sejarah-fe');
            // ORGANISASI
            Route::get('/organisasi', [HomeFeController::class, 'profile_organisasi'])->name('organisasi-fe');
            // ORGANISASI
            Route::get('/tentang-kami', [HomeFeController::class, 'profile_tentang'])->name('tentang-fe');
        });

        // MENU lAYANAN
        Route::prefix('/layanan')->group(function () {
            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'layanan_layanan'])->name('layanan-fe');
        });

        // MENU INFORMASI PUBLIK
        Route::prefix('/informasi-publik')->group(function () {

            Route::get('/', [HomeFeController::class, 'infopublik_index'])->name('informasi-publik-index-fe');

            Route::match(['get', 'post'], '/peraturan', [HomeFeController::class, 'infopublik_peraturan_index'])->name('informasi-publik-peraturan-index-fe');

            Route::get('/detail/peraturan/{peraturan}', [HomeFeController::class, 'infopublik_peraturan_detail'])->name('informasi-publik-peraturan-detail-fe');

            Route::match(['get', 'post'], '/pedoman', [HomeFeController::class, 'infopublik_pedoman_index'])->name('informasi-publik-pedoman-index-fe');

            Route::match(['get', 'post'], '/aplikasi', [HomeFeController::class, 'infopublik_aplikasi_index'])->name('informasi-publik-aplikasi-index-fe');
        });

        // MENU FAQ
        Route::prefix('/faq')->group(function () {

            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'faq_index'])->name('faq-index-fe');
        });

        // MENU KEGIATAN
        Route::prefix('/kegiatan')->group(function () {
            Route::match(['get', 'post'], '/', [HomeFeController::class, 'kegiatan_index'])->name('kegiatan-index-fe');
            Route::get('/detail/{kegiatan}/{ranstring}', [HomeFeController::class, 'kegiatan_detail'])->name('kegiatan-detail-fe');
        });

        // MENU PUBLIKASI
        Route::prefix('/publikasi')->group(function () {

            // INDEX
            Route::get('/', [HomeFeController::class, 'publikasi_index'])->name('publikasi-index-fe');

            // BERITA INDEX
            Route::match(['get', 'post'], '/berita', [HomeFeController::class, 'publikasi_index_berita'])->name('publikasi-index-berita-fe');

            // WARTA INDEX
            Route::match(['get', 'post'], '/warta', [HomeFeController::class, 'publikasi_index_warta'])->name('publikasi-index-warta-fe');

            // ARTIKEL INDEX
            Route::match(['get', 'post'], '/artikel', [HomeFeController::class, 'publikasi_index_artikel'])->name('publikasi-index-artikel-fe');
            // BERITA
            Route::get('/berita/{publikasi}', [HomeFeController::class, 'publikasi_berita'])->name('berita-fe');
            // WARTA
            Route::get('/warta/{publikasi}', [HomeFeController::class, 'publikasi_warta'])->name('warta-fe');
            // ARTIKEL
            Route::get('/artikel/{publikasi}', [HomeFeController::class, 'publikasi_artikel'])->name('artikel-fe');

            // FILTER KATEGORI BERITA TERKINI
            Route::match(['get', 'post'], '/berita/kategori/{kategori}', [HomeFeController::class, 'publikasi_berita_kategori'])->name('berita-kategori-fe');
            Route::match(['get', 'post'], '/warta/kategori/{kategori}', [HomeFeController::class, 'publikasi_warta_kategori'])->name('warta-kategori-fe');
            Route::match(['get', 'post'], '/artikel/kategori/{kategori}', [HomeFeController::class, 'publikasi_artikel_kategori'])->name('artikel-kategori-fe');
        });
    }
);

// Sitemap XML — di luar grup log.visitor karena endpoint ini untuk crawler,
// bukan halaman yang perlu tercatat sebagai kunjungan pengguna.
Route::get('/sitemap.xml', [HomeFeController::class, 'sitemap'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| 2. BACK END
|--------------------------------------------------------------------------
| Seluruh segmen URL di bawah /backend memakai token acak dari
| config/cms_url.php (bukan nama modul asli) supaya struktur URL CMS tidak
| bisa ditebak dari luar. Nama route TETAP nama logis biasa — jangan pernah
| hardcode path backend, selalu pakai helper route().
*/
$u = fn (string $key): string => config('cms_url.'.$key);

Route::group(['prefix' => 'backend', 'middleware' => ['auth']], function () use ($u) {

    // Streaming gambar ter-otentikasi khusus dalam CMS (lihat MediaBlobController)
    Route::get('/'.$u('media_blob').'/{folder}/{filename}', [MediaBlobController::class, 'show'])
        ->where('filename', '.*')
        ->name('media.blob');

    // Upload gambar dari dalam CKEditor (semua role penulis konten)
    Route::post('/'.$u('ck_upload'), [MediaController::class, 'ckeditorUpload'])->name('media.ck-upload');

    // 2.1 INTERFACE BACKEND
    Route::prefix('/'.$u('interface'))->group(function () use ($u) {

        // Routes untuk semua role (termasuk TAMU)
        Route::get('/'.$u('dashboard'), [HomeBeController::class, 'index'])->name('home');

        // ========== ROUTES UNTUK ADMINISTRATOR ==========
        Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
            // 2.1.1 PENGEMBANG
            Route::prefix('/'.$u('tim'))->group(function () use ($u) {
                Route::resource($u('pengembang'), PengembangController::class)
                    ->names('pengembang')
                    ->parameters([$u('pengembang') => 'id']);
            });

            // 2.1.3 USERS
            Route::resource($u('users'), UserController::class)
                ->names('users')
                ->parameters([$u('users') => 'id']);

            // 2.1.7 MENU LAYANAN
            Route::prefix('/'.$u('layanan_group'))->group(function () use ($u) {
                Route::resource($u('layanan'), LayananController::class)
                    ->names('layanan')
                    ->parameters([$u('layanan') => 'id']);
            });

            // 2.1.8 MENU INFORMASI PUBLIK
            Route::prefix('/'.$u('infopub_group'))->group(function () use ($u) {
                // 2.1.8.1 INFORMASI PUBLIK
                Route::resource($u('infopub'), InformasiPublikController::class)
                    ->names('informasi-publik')
                    ->parameters([$u('infopub') => 'id']);
                Route::get('/'.$u('infopub_home_index'), [InformasiPublikController::class, 'indexHome'])->name('informasi-publik.index-home');
                Route::get('/'.$u('infopub_home_create'), [InformasiPublikController::class, 'create_home'])->name('informasi-publik.create-home');
                Route::get('/{infopub}/'.$u('infopub_home_edit'), [InformasiPublikController::class, 'edit_home'])->name('informasi-publik.edit-home');
                Route::post('/'.$u('infopub_home_create'), [InformasiPublikController::class, 'store_home'])->name('informasi-publik.store-home');
                Route::put('/{infopub}/'.$u('infopub_home_edit'), [InformasiPublikController::class, 'update_home'])->name('informasi-publik.update-home');
                Route::delete('/{infopub}/'.$u('infopub_home_delete'), [InformasiPublikController::class, 'delete_home'])->name('informasi-publik.delete-home');

                // 2.1.8.2 PERATURAN
                Route::resource($u('peraturan'), PeraturanController::class)
                    ->names('peraturan')
                    ->parameters([$u('peraturan') => 'id']);

                // 2.1.8.3 PORTAL APLIKASI
                Route::resource($u('aplikasi'), AplikasiController::class)
                    ->names('aplikasi')
                    ->parameters([$u('aplikasi') => 'id']);

                // 2.1.8.4 PEDOMAN
                Route::resource($u('pedoman'), PedomanController::class)
                    ->names('pedoman')
                    ->parameters([$u('pedoman') => 'id']);
            });

            // 2.1.11 MEDSOS
            Route::resource($u('medsos'), MedsosController::class)
                ->names('medsos')
                ->parameters([$u('medsos') => 'id']);

            // 2.1.12 LOGIN GAMBAR
            Route::resource($u('loggambar'), LoginController::class)
                ->names('loggambar')
                ->parameters([$u('loggambar') => 'id']);
        });

        // ========== ROUTES UNTUK REDAKTUR & EDITOR & ADMINISTRATOR ==========
        Route::middleware(['role:ADMINISTRATOR|REDAKTUR|EDITOR'])->group(function () use ($u) {
            // 2.1.6 MENU PROFILE
            Route::prefix('/'.$u('profile_group'))->group(function () use ($u) {
                // 2.1.6.1 TENTANG
                Route::resource($u('tentang'), TentangController::class)
                    ->names('tentang')
                    ->parameters([$u('tentang') => 'id']);
                // 2.1.6.2 VISI DAN MISI
                Route::resource($u('visimisi'), VisiMisiController::class)
                    ->names('visi-misi')
                    ->parameters([$u('visimisi') => 'id']);
                // 2.1.6.3 SEJARAH
                Route::resource($u('sejarah'), SejarahController::class)
                    ->names('sejarah')
                    ->parameters([$u('sejarah') => 'id']);
                // 2.1.6.4 STRUKTUR ORGANISASI (hirarki card + branch)
                Route::prefix($u('struktur'))->name('struktur-jabatan.')->group(function () use ($u) {
                    Route::get('/', [StrukturJabatanController::class, 'index'])->name('index');
                    Route::post('/'.$u('jabatan'), [StrukturJabatanController::class, 'storeJabatan'])->name('jabatan.store');
                    Route::put('/'.$u('jabatan').'/{id}', [StrukturJabatanController::class, 'updateJabatan'])->name('jabatan.update');
                    Route::delete('/'.$u('jabatan').'/{id}', [StrukturJabatanController::class, 'destroyJabatan'])->name('jabatan.destroy');
                    Route::post('/'.$u('pejabat'), [StrukturJabatanController::class, 'storePejabat'])->name('pejabat.store');
                    Route::put('/'.$u('pejabat').'/{id}', [StrukturJabatanController::class, 'updatePejabat'])->name('pejabat.update');
                    Route::delete('/'.$u('pejabat').'/{id}', [StrukturJabatanController::class, 'destroyPejabat'])->name('pejabat.destroy');
                });
            });

            // 2.1.9 MENU FAQ
            Route::prefix('/'.$u('faq_group'))->group(function () use ($u) {
                Route::resource($u('faq'), FAQController::class)
                    ->names('faq')
                    ->parameters([$u('faq') => 'id']);
            });
        });

        // ========== ROUTES UNTUK REDAKTUR & ADMINISTRATOR ==========
        Route::middleware(['role:ADMINISTRATOR|REDAKTUR'])->group(function () use ($u) {
            // 2.1.10 REFERENSI
            Route::prefix('/'.$u('referensi_group'))->group(function () use ($u) {
                // 2.1.10.1 REF KATEGORI
                Route::resource($u('kategori'), RefKategoriController::class)
                    ->names('kategori')
                    ->parameters([$u('kategori') => 'id']);
                // 2.1.10.2 REF STATUS
                Route::resource($u('status'), RefStatusController::class)
                    ->names('status')
                    ->parameters([$u('status') => 'id']);
                // 2.1.10.3 REF TIPE
                Route::resource($u('tipe'), RefTipeController::class)
                    ->names('tipe')
                    ->parameters([$u('tipe') => 'id']);
                // 2.1.10.4 REF JENIS PERATURAN
                Route::resource($u('jenis_peraturan'), RefJenisPeraturanController::class)
                    ->names('jenis-peraturan')
                    ->parameters([$u('jenis_peraturan') => 'id']);
                // 2.1.10.5 REF STATUS PERATURAN
                Route::resource($u('status_peraturan'), RefPeraturanStatusController::class)
                    ->names('status-peraturan')
                    ->parameters([$u('status_peraturan') => 'id']);
            });
        });

        // ========== ROUTES UNTUK REDAKTUR & EDITOR & ADMINISTRATOR & HUMAS ==========
        Route::middleware(['role:ADMINISTRATOR|REDAKTUR|EDITOR|HUMAS'])->group(function () use ($u) {
            // 2.1.4 PUBLIKASI
            // NB: rute export harus didaftarkan SEBELUM Route::resource, kalau
            // tidak akan "tertangkap" oleh wildcard {publikasi} milik show().
            Route::get('/'.$u('publikasi').'/'.$u('pub_export'), [PublikasiController::class, 'exportExcel'])->name('publikasi.export');
            Route::resource($u('publikasi'), PublikasiController::class)
                ->names('publikasi')
                ->parameters([$u('publikasi') => 'publikasi']);
            Route::get('/'.$u('publikasi').'/{publikasi}/'.$u('pub_revisions'), [PublikasiController::class, 'revisions'])->name('publikasi.revisions');
            Route::post('/'.$u('publikasi').'/{publikasi}/'.$u('pub_revisions').'/{revision}/'.$u('pub_revision_restore'), [PublikasiController::class, 'restoreRevision'])->name('publikasi.revisions.restore');
            Route::get('/'.$u('pub_sampah'), [PublikasiController::class, 'publikasiSampah'])->name('publikasi.sampah');
            Route::post('/{publikasi}/'.$u('pub_restore'), [PublikasiController::class, 'restorePublikasi'])->name('publikasi.restore');
            Route::delete('/{publikasi}/'.$u('pub_force_delete'), [PublikasiController::class, 'forceDeletePublikasi'])->name('publikasi.force-delete');
            Route::post('/'.$u('pub_restore_all'), [PublikasiController::class, 'restoreAllPublikasi'])->name('publikasi.restore-all');

            // 2.1.5 KEGIATAN
            Route::resource($u('kegiatan'), KegiatanController::class)
                ->names('kegiatan')
                ->parameters([$u('kegiatan') => 'id']);
        });
    });

    // NB: Seluruh fitur CMS di bawah ini sengaja dipindah ke dalam grup
    // prefix('backend') supaya SEMUA halaman yang diakses lewat CMS
    // konsisten berada di bawah /backend/*, bukan tersebar di root.

    // 3. BACKUP
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('backups'), [BackupController::class, 'index'])->name('backups.index');
        Route::post($u('backups'), [BackupController::class, 'create'])->name('backups.create');
        Route::post($u('backups').'/'.$u('backups_cleanup'), [BackupController::class, 'cleanup'])->name('backups.cleanup');
        Route::delete($u('backups').'/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
        Route::get($u('backups').'/{filename}/'.$u('backups_download'), [BackupController::class, 'download'])->name('backups.download');
    });

    // 4. ACTIVITY LOG
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('activity_log'), [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get($u('activity_log').'/'.$u('activity_export'), [ActivityLogController::class, 'exportExcel'])->name('activity-log.export');
        Route::delete($u('activity_log').'/{id}', [ActivityLogController::class, 'destroy'])->name('activity-log.destroy');
        Route::post($u('activity_log').'/'.$u('activity_clean'), [ActivityLogController::class, 'clean'])->name('activity-log.clean');
    });

    // 5. PENGATURAN (Contact Info & Footer Links)
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('contact_info'), [ContactInfoController::class, 'index'])->name('contact-info.index');
        Route::get($u('contact_info').'/{id}/'.$u('verb_edit'), [ContactInfoController::class, 'edit'])->name('contact-info.edit');
        Route::put($u('contact_info').'/{id}', [ContactInfoController::class, 'update'])->name('contact-info.update');

        Route::resource($u('footer_link'), FooterLinkController::class)
            ->names('footer-link')
            ->parameters([$u('footer_link') => 'id']);
    });

    // 6. VISITORS (monitoring pengunjung frontend — khusus ADMINISTRATOR)
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('visitors'), [VisitorController::class, 'index'])->name('visitors.index');
        Route::get($u('visitors').'/'.$u('visitors_export'), [VisitorController::class, 'exportExcel'])->name('visitors.export');
        Route::post($u('visitors').'/'.$u('visitors_clean'), [VisitorController::class, 'clean'])->name('visitors.clean');
    });

    // 6b. MEDIA LIBRARY (khusus ADMINISTRATOR)
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('media'), [MediaController::class, 'index'])->name('media.index');
        Route::post($u('media').'/'.$u('media_sync'), [MediaController::class, 'sync'])->name('media.sync');
        Route::delete($u('media').'/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
    });

    // 6c. SYSTEM MONITOR (pengecekan resource aplikasi & database, khusus ADMINISTRATOR)
    Route::middleware(['role:ADMINISTRATOR'])->group(function () use ($u) {
        Route::get($u('system_monitor'), [SystemMonitorController::class, 'index'])->name('system-monitor.index');
        Route::get($u('system_monitor').'/'.$u('system_monitor_data'), [SystemMonitorController::class, 'data'])->name('system-monitor.data');
    });

    // 7. KEAMANAN AKUN — 2FA (self-service, semua role yang sudah login)
    Route::prefix($u('security'))->group(function () use ($u) {
        Route::get($u('twofa'), [TwoFactorController::class, 'index'])->name('two-factor.index');
        Route::post($u('twofa').'/'.$u('twofa_enable'), [TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post($u('twofa').'/'.$u('twofa_cancel'), [TwoFactorController::class, 'cancel'])->name('two-factor.setup.cancel');
        Route::post($u('twofa').'/'.$u('twofa_confirm'), [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
        Route::delete($u('twofa'), [TwoFactorController::class, 'disable'])->name('two-factor.disable');
        Route::post($u('twofa').'/'.$u('twofa_regen'), [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.regenerate-codes');
    });
});

Route::get('/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tantangan 2FA saat login — TIDAK pakai middleware auth (user belum login),
// dilindungi oleh session pending + rate limit sendiri di controller.
Route::get('/2fa/verifikasi', [AuthController::class, 'twoFactorChallenge'])->name('two-factor.challenge');
Route::post('/2fa/verifikasi', [AuthController::class, 'twoFactorVerify'])->middleware('throttle:10,1')->name('two-factor.verify');
Route::post('/2fa/batal', [AuthController::class, 'twoFactorCancel'])->name('two-factor.challenge.cancel');
