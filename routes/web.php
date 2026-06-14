<?php

use App\Http\Controllers\AksesArsipController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\KirimArsipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
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
    $users = User::where('jabatan', '!=', 'admin')->get();
    return view('welcome', compact('users'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Arsip
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');

    // Folders
    Route::prefix('folders')->group(function () {
        Route::get('/show/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::post('/store', [FolderController::class, 'store'])->name('folders.store');
        Route::put('/update/{folder}', [FolderController::class, 'update'])->name('folders.update');
        Route::delete('/destroy/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    });

    // Files
    Route::prefix('files')->group(function () {
        Route::post('/store', [FileController::class, 'store'])->name('files.store');
        Route::put('/update/{file}', [FileController::class, 'update'])->name('files.update');
        Route::delete('/destroy/{file}', [FileController::class, 'destroy'])->name('files.destroy');
        Route::get('/open/{file}', [FileController::class, 'open'])->name('files.open');
        Route::get('/download/{file}', [FileController::class, 'download'])->name('files.download');
        Route::put('/move/{file}', [FileController::class, 'move'])->name('files.move');
    });
});

Route::middleware(['role:admin,kepala_madrasah,kaur'])->group(function () {
    // Akses Arsip
    Route::prefix('akses-arsip')->group(function () {
        Route::get('/', [AksesArsipController::class, 'index'])->name('akses-arsip.index');
        Route::get('/show/{user}', [AksesArsipController::class, 'show'])->name('akses-arsip.show');
        Route::get('/open/{user}/folder/{folder}', [AksesArsipController::class, 'openFolder'])->name('akses-arsip.openFolder');
    });

    // Kirim Arsip
    Route::prefix('kirim-arsip')->group(function () {
        Route::get('/', [KirimArsipController::class, 'index'])->name('kirim-arsip.index');
        Route::get('/create', [KirimArsipController::class, 'create'])->name('kirim-arsip.create');
        Route::post('/store', [KirimArsipController::class, 'store'])->name('kirim-arsip.store');
        Route::get('/show/{batchId}', [KirimArsipController::class, 'show'])->name('kirim-arsip.show');
        Route::delete('/destroy/{batchId}', [KirimArsipController::class, 'destroy'])->name('kirim-arsip.destroy');
    });
});

Route::middleware(['role:admin'])->group(function () {
    // User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });
});


require __DIR__ . '/auth.php';
