<?php

use App\Http\Controllers\Artikel\ArtikelController;
use App\Http\Controllers\Berita\BeritaController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\MenuProfile\SejarahController;
use App\Http\Controllers\MenuProfile\StrukturOrganisasiController;
use App\Http\Controllers\MenuProfile\TentangController;
use App\Http\Controllers\MenuProfile\VisiMisiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Referensi\RefKategoriController;
use App\Http\Controllers\Referensi\RefStatusController;
use App\Http\Controllers\UserManajemen\UserController;
use App\Http\Controllers\Warta\WartaController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// BACK END

Route::group(['prefix' => 'backend', 'middleware' => ['auth']], function () {

    Route::get('/home', function () {
        return view('backend.dashboard');
    })->name('home');

    // INTERFACE BACKEND
    Route::prefix('/romadan-interface')->group(function () {

        // USERS
        Route::resource('users', UserController::class);

        // BERITA
        Route::resource('/berita', BeritaController::class);
        Route::get('/berita-sampah', [BeritaController::class, 'beritaSampah'])->name('berita.sampah');
        Route::post('/{beritum}/restore-berita', [BeritaController::class, 'restore'])->name('berita.restore');
        Route::delete('/{beritum}/force-delete-berita', [BeritaController::class, 'forceDelete'])->name('berita.force-delete');
        Route::post('/restore-all-berita', [BeritaController::class, 'restoreAll'])->name('berita.restore-all');

        // ARTIKEL
        Route::resource('/artikel', ArtikelController::class);
        Route::get('/artikel-sampah', [ArtikelController::class, 'artikelSampah'])->name('artikel.sampah');
        Route::post('/{artikum}/restore-artikel', [ArtikelController::class, 'restore'])->name('artikel.restore');
        Route::delete('/{artikum}/force-delete-artikel', [ArtikelController::class, 'forceDelete'])->name('artikel.force-delete');
        Route::post('/restore-all-artikel', [ArtikelController::class, 'restoreAll'])->name('artikel.restore-all');

        // WARTA
        Route::resource('/warta', WartaController::class);
        Route::get('/warta-sampah', [WartaController::class, 'wartaSampah'])->name('warta.sampah');
        Route::post('/{beritum}/restore-warta', [WartaController::class, 'restore'])->name('warta.restore');
        Route::delete('/{beritum}/force-delete-warta', [WartaController::class, 'forceDelete'])->name('warta.force-delete');
        Route::post('/restore-all-warta', [WartaController::class, 'restoreAll'])->name('warta.restore-all');



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

        // REFERENSI
        Route::resource('kategori', RefKategoriController::class);
        Route::resource('status', RefStatusController::class);
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
