<?php

use App\Http\Controllers\Backend\HomeBeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Frontend\HomeFeController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Medsos\MedsosController;
use App\Http\Controllers\MenuFAQ\FAQController;
use App\Http\Controllers\MenuInformasiPublik\AplikasiController;
use App\Http\Controllers\MenuInformasiPublik\InformasiPublikController;
use App\Http\Controllers\MenuInformasiPublik\PeraturanController;
use App\Http\Controllers\MenuKegiatan\KegiatanController;
use App\Http\Controllers\MenuLayanan\LayananController;
use App\Http\Controllers\MenuProfile\SejarahController;
use App\Http\Controllers\MenuProfile\StrukturOrganisasiController;
use App\Http\Controllers\MenuProfile\TentangController;
use App\Http\Controllers\MenuProfile\VisiMisiController;
use App\Http\Controllers\MenuPublikasi\PublikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Referensi\RefJenisPeraturanController;
use App\Http\Controllers\Referensi\RefKategoriController;
use App\Http\Controllers\Referensi\RefPeraturanStatusController;
use App\Http\Controllers\Referensi\RefStatusController;
use App\Http\Controllers\Referensi\RefTipeController;
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
    ['prefix' => '/'],
    function () {

        // HOME
        Route::get('/', [HomeFeController::class, 'index'])->name('homefe');


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

            Route::get('/pedoman', [HomeFeController::class, 'infopublik_pedoman_index'])->name('informasi-publik-pedoman-index-fe');

            Route::match(['get', 'post'], '/aplikasi', [HomeFeController::class, 'infopublik_aplikasi_index'])->name('informasi-publik-aplikasi-index-fe');
        });

        // MENU FAQ
        Route::prefix('/faq')->group(function () {

            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'faq_index'])->name('faq-index-fe');
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


// 2. BACK END
Route::group(['prefix' => 'backend', 'middleware' => ['auth']], function () {

    // 2.1 INTERFACE BACKEND
    Route::prefix('/romadan-interface')->group(function () {

        // 2.1.1 PENGEMBANG

        Route::prefix('/tim')->group(function () {
            Route::resource('pengembang', PengembangController::class);
        });

        // 2.1.2 HOME
        Route::get('/dashboard', [HomeBeController::class, 'index'])->name('home');

        // 2.1.3 USERS
        Route::resource('users', UserController::class);

        // 2.1.4 PUBLIKASI
        Route::resource('publikasi', PublikasiController::class);

        Route::get('/publikasi-sampah', [PublikasiController::class, 'publikasiSampah'])->name('publikasi.sampah');
        Route::post('/{publikasi}/restore-publikasi', [PublikasiController::class, 'restorePublikasi'])->name('publikasi.restore');
        Route::delete('/{publikasi}/force-delete-publikasi', [PublikasiController::class, 'forceDeletePublikasi'])->name('publikasi.force-delete');
        Route::post('/restore-all-publikasi', [PublikasiController::class, 'restoreAllPublikasi'])->name('publikasi.restore-all');

        // 2.1.5 FILE
        Route::resource('file', FileController::class);
        Route::get('/file-sampah', [FileController::class, 'fileSampah'])->name('file.sampah');
        Route::post('/{file}/restore-file', [FileController::class, 'restore'])->name('file.restore');
        Route::delete('/{file}/force-delete', [FileController::class, 'forceDeleteSampah'])->name('file.force-delete-sampah');
        Route::post('/restore-all-file', [FileController::class, 'restoreAll'])->name('file.restore-all');


        // 2.1.6 MENU PROFILE
        Route::prefix('/profile')->group(function () {
            // 2.1.6.1 TENTANG
            Route::resource('tentang', TentangController::class);
            // 2.1.6.2 VISI DAN MISI
            Route::resource('visi-misi', VisiMisiController::class);
            // 2.1.6.3 SEJARAH
            Route::resource('sejarah', SejarahController::class);
            // 2.1.6.4 STRUKTUR ORGANISASI
            Route::resource('struktur-organisasi', StrukturOrganisasiController::class);
        });

        // 2.1.7 MENU LAYANAN
        Route::prefix('/layanan')->group(function () {
            // 2.1.7.1LAYANAN
            Route::resource('layanan', LayananController::class);
        });


        // 2.1.8 MENU INFORMASI PUBLIK
        Route::prefix('/informasi-publik')->group(function () {

            //2.1.8.1 INFORMASI PUBLIK
            Route::resource('informasi-publik', InformasiPublikController::class);
            Route::get('/index-home', [InformasiPublikController::class, 'indexHome'])->name('informasi-publik.index-home');
            Route::get('/create-home', [InformasiPublikController::class, 'create_home'])->name('informasi-publik.create-home');
            Route::get('/{infopub}/edit-home', [InformasiPublikController::class, 'edit_home'])->name('informasi-publik.edit-home');
            Route::post('/create-home', [InformasiPublikController::class, 'store_home'])->name('informasi-publik.store-home');
            Route::put('/{infopub}/edit-home', [InformasiPublikController::class, 'update_home'])->name('informasi-publik.update-home');
            Route::delete('/{infopub}/informasi-publik-home', [InformasiPublikController::class, 'delete_home'])->name('informasi-publik.delete-home');

            // 2.1.8.2 PERATURAN BACKEND
            Route::resource('peraturan', PeraturanController::class);

            // 2.1.8.3 PORTAL APLIKASI BACKEND
            Route::resource('aplikasi', AplikasiController::class);
        });


        // 2.1.9 MENU FAQ

        Route::prefix('/faq')->group(function () {
            // 2.1.9.1 FAQ
            Route::resource('faq', FAQController::class);
        });

        // 2.1.10 REFERENSI
        Route::prefix('/referensi')->group(function () {
                // 2.1.10.1 REF KATEGORI
            Route::resource('kategori', RefKategoriController::class);

                // 2.1.10.2 REF STATUS
            Route::resource('status', RefStatusController::class);

                // 2.1.10.3 REF TIPE
            Route::resource('tipe', RefTipeController::class);

                // 2.1.10.4 REF JENIS PERATURAN
            Route::resource('jenis-peraturan', RefJenisPeraturanController::class);

                // 2.1.10.5 REF STATUS PERATURAN
            Route::resource('status-peraturan', RefPeraturanStatusController::class);
        });



        // 2.1.11 MEDSOS
        Route::resource('medsos', MedsosController::class);

        // 2.1.12 LOGIN GAMBAR
        Route::resource('loggambar', LoginController::class);
    });
});


// 3. BACKUP

Route::middleware(['auth'])->group(function () {
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('backups', [BackupController::class, 'create'])->name('backups.create');
    Route::delete('backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    Route::get('backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
    Route::post('backups/cleanup', [BackupController::class, 'cleanup'])->name('backups.cleanup');
});
require __DIR__ . '/auth.php';
