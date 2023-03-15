<?php

use App\Http\Controllers\Berita\BeritaController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Referensi\RefKategoriController;
use App\Http\Controllers\Referensi\RefStatusController;
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


    // USERS
    Route::resource('users', UserController::class);

    // BERITA
    Route::prefix('/berita')->group(function () {
        Route::resource('/berita', BeritaController::class);

        Route::get('/berita-sampah', [BeritaController::class, 'beritaSampah'])->name('berita.sampah');
        Route::post('/{beritum}/restore', [BeritaController::class, 'restore'])->name('berita.restore');
        Route::delete('/{beritum}/force-delete', [BeritaController::class, 'forceDelete'])->name('berita.force-delete');
        Route::post('/restore-all', [BeritaController::class, 'restoreAll'])->name('berita.restore-all');
    });


    // FILE
    Route::prefix('/file')->group(function () {
        Route::resource('file', FileController::class);

        Route::get('/file-sampah', [FileController::class, 'fileSampah'])->name('file.sampah');
        Route::post('/{file}/restore', [FileController::class, 'restore'])->name('file.restore');
        Route::delete('/{file}/force-delete', [FileController::class, 'forceDelete'])->name('file.force-delete');
        Route::post('/restore-all', [FileController::class, 'restoreAll'])->name('file.restore-all');
    });

    // REFERENSI KATEGORI DAN STATUS
    Route::prefix('/referensi')->group(function () {
        Route::resource('kategori', RefKategoriController::class);
        Route::resource('status', RefStatusController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
