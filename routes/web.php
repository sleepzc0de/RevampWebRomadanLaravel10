<?php

use App\Http\Controllers\Backend\HomeBeController;
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

// Route::get('/coba-login', function () {
//     return view('cobalogin');
// });


// FRONT END

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

        // MENU KEGIATAN
        Route::prefix('/kegiatan')->group(function () {
            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'kegiatan_index'])->name('kegiatan-index-fe');
            Route::post('/', [HomeFeController::class, 'kegiatan_index'])->name('kegiatan-index-fe');
            // Route::get('/search', [HomeFeController::class, 'kegiatan_search'])->name('kegiatan-search-fe');
            // VISI DAN MISI
            Route::get('/detail/{kegiatan}', [HomeFeController::class, 'kegiatan_detail'])->name('kegiatan-detail-fe');
        });

        // MENU INFORMASI PUBLIK
        Route::prefix('/informasi-publik')->group(function () {
            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'infopublik_index'])->name('informasi-publik-index-fe');

            Route::get('/peraturan', [HomeFeController::class, 'infopublik_peraturan_index'])->name('informasi-publik-peraturan-index-fe');
            Route::post('/peraturan', [HomeFeController::class, 'infopublik_peraturan_index'])->name('informasi-publik-peraturan-index-fe');
            Route::get('/detail/peraturan/{peraturan}', [HomeFeController::class, 'infopublik_peraturan_detail'])->name('informasi-publik-peraturan-detail-fe');

            Route::get('/pedoman', [HomeFeController::class, 'infopublik_pedoman_index'])->name('informasi-publik-pedoman-index-fe');

            Route::get('/aplikasi', [HomeFeController::class, 'infopublik_aplikasi_index'])->name('informasi-publik-aplikasi-index-fe');
            Route::post('/aplikasi', [HomeFeController::class, 'infopublik_aplikasi_index'])->name('informasi-publik-aplikasi-index-fe');
            // Route::get('/search', [HomeFeController::class, 'kegiatan_search'])->name('kegiatan-search-fe');
        });

        // MENU FAQ
        Route::prefix('/faq')->group(function () {
            // VISI DAN MISI
            Route::get('/', [HomeFeController::class, 'faq_index'])->name('faq-index-fe');
            // Route::get('/search', [HomeFeController::class, 'kegiatan_search'])->name('kegiatan-search-fe');
        });

        // MENU PUBLIKASI
        Route::prefix('/publikasi')->group(function () {
            // INDEX
            Route::get('/', [HomeFeController::class, 'publikasi_index'])->name('publikasi-index-fe');
            // BERITA INDEX
            Route::get('/berita', [HomeFeController::class, 'publikasi_index_berita'])->name('publikasi-index-berita-fe');
            Route::post('/berita', [HomeFeController::class, 'publikasi_index_berita'])->name('publikasi-index-berita-fe');
            // WARTA INDEX
            Route::get('/warta', [HomeFeController::class, 'publikasi_index_warta'])->name('publikasi-index-warta-fe');
            Route::post('/warta', [HomeFeController::class, 'publikasi_index_warta'])->name('publikasi-index-warta-fe');
            // ARTIKEL INDEX
            Route::get('/artikel', [HomeFeController::class, 'publikasi_index_artikel'])->name('publikasi-index-artikel-fe');
            Route::post('/artikel', [HomeFeController::class, 'publikasi_index_artikel'])->name('publikasi-index-artikel-fe');
            // BERITA
            Route::get('/berita/{publikasi}', [HomeFeController::class, 'publikasi_berita'])->name('berita-fe');
            // WARTA
            Route::get('/warta/{publikasi}', [HomeFeController::class, 'publikasi_warta'])->name('warta-fe');
            // ARTIKEL
            Route::get('/artikel/{publikasi}', [HomeFeController::class, 'publikasi_artikel'])->name('artikel-fe');
            // FILTER KATEGORI BERITA TERKINI

            Route::get('/berita/kategori/{kategori}', [HomeFeController::class, 'publikasi_berita_kategori'])->name('berita-kategori-fe');


            // COBA

            // Route::get('beritacoba/{slug}', function ($slug) {
            //     $result =   DB::table('campains')->where('slug', $slug)->get();
            //     // .... call controller etc...
            // });
        });
    }
);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// BACK END

Route::group(['prefix' => 'backend', 'middleware' => ['auth']], function () {

    // Route::get('/home', function () {
    //     return view('backend.dashboard');
    // })->name('home');

    // INTERFACE BACKEND
    Route::prefix('/romadan-interface')->group(function () {

        // HOME
        Route::get('/dashboard', [HomeBeController::class, 'index'])->name('home');


        // USERS
        Route::resource('users', UserController::class);

        // PUBLIKASI
        Route::resource('/publikasi', PublikasiController::class);
        Route::get('/publikasi-sampah', [PublikasiController::class, 'publikasiSampah'])->name('publikasi.sampah');
        Route::post('/{publikasi}/restore-publikasi', [PublikasiController::class, 'restorePublikasi'])->name('publikasi.restore');
        Route::delete('/{publikasi}/force-delete-publikasi', [PublikasiController::class, 'forceDeletePublikasi'])->name('publikasi.force-delete');
        Route::post('/restore-all-publikasi', [PublikasiController::class, 'restoreAllPublikasi'])->name('publikasi.restore-all');

        // FILE
        Route::resource('file', FileController::class);
        Route::get('/file-sampah', [FileController::class, 'fileSampah'])->name('file.sampah');
        Route::post('/{file}/restore-file', [FileController::class, 'restore'])->name('file.restore');
        Route::delete('/{file}/force-delete', [FileController::class, 'forceDeleteSampah'])->name('file.force-delete-sampah');
        Route::post('/restore-all-file', [FileController::class, 'restoreAll'])->name('file.restore-all');


        // MENU PROFILE
        Route::prefix('/profile')->group(function () {
            // TENTANG
            Route::resource('tentang', TentangController::class);
            // VISI DAN MISI
            Route::resource('visi-misi', VisiMisiController::class);
            // SEJARAH
            Route::resource('sejarah', SejarahController::class);
            // STRUKTUR ORGANISASI
            Route::resource('struktur-organisasi', StrukturOrganisasiController::class);
        });

        // MENU LAYANAN
        Route::prefix('/layanan')->group(function () {
            // TENTANG
            Route::resource('layanan', LayananController::class);
        });

        // MENU KEGIATAN
        Route::prefix('/kegiatan')->group(function () {

            Route::resource('kegiatan', KegiatanController::class);
        });

        // MENU INFORMASI PUBLIK

        Route::prefix('/informasi-publik')->group(function () {

            Route::resource('informasi-publik', InformasiPublikController::class);
            Route::get('/index-home', [InformasiPublikController::class, 'indexHome'])->name('informasi-publik.index-home');
            Route::get('/create-home', [InformasiPublikController::class, 'create_home'])->name('informasi-publik.create-home');
            Route::post('/create-home', [InformasiPublikController::class, 'store_home'])->name('informasi-publik.store-home');
            Route::delete('/{infopub}/informasi-publik-home', [InformasiPublikController::class, 'delete_home'])->name('informasi-publik.delete-home');

            // PERATURAN BACKEND
            Route::resource('peraturan', PeraturanController::class);
            // PORTAL APLIKASI BACKEND
            Route::resource('aplikasi', AplikasiController::class);
        });


        // MENU FAQ

        Route::prefix('/faq')->group(function () {

            Route::resource('faq', FAQController::class);
        });

        // REFERENSI
        Route::resource('kategori', RefKategoriController::class);
        Route::resource('status', RefStatusController::class);
        Route::resource('tipe', RefTipeController::class);
        // MENU KEGIATAN
        Route::prefix('/referensi')->group(function () {

            Route::resource('jenis-peraturan', RefJenisPeraturanController::class);
            Route::resource('status-peraturan', RefPeraturanStatusController::class);
        });



        // MEDSOS
        Route::resource('medsos', MedsosController::class);

        // LOGIN GAMBAR
        Route::resource('loggambar', LoginController::class);
    });


    // FILE
    // Route::prefix('/file')->group(function () {
    //     Route::resource('file', FileController::class);

    //     Route::get('/file-sampah', [FileController::class, 'fileSampah'])->name('file.sampah');
    //     Route::post('/{file}/restore', [FileController::class, 'restore'])->name('file.restore');
    //     Route::delete('/{file}/force-delete', [FileController::class, 'forceDelete'])->name('file.force-delete');
    //     Route::post('/restore-all', [FileController::class, 'restoreAll'])->name('file.restore-all');
    // });

    // REFERENSI KATEGORI DAN STATUS
    // Route::prefix('/referensi')->group(function () {
    //     Route::resource('kategori', RefKategoriController::class);
    //     Route::resource('status', RefStatusController::class);
    // });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
