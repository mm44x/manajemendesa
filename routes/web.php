<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\AnggotaKeluargaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\WargaExportController;



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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Route::get('/users', [UserController::class, 'index']);
    });

    Route::middleware(['auth', 'role:admin,sekretaris'])->group(function () {
        Route::resource('kartu-keluarga', KartuKeluargaController::class);
    });

    Route::get('/wilayah/provinsi', [WilayahController::class, 'getProvinsi']);
    Route::get('/wilayah/kabupaten', [WilayahController::class, 'getKabupaten']);
    Route::get('/wilayah/kecamatan', [WilayahController::class, 'getKecamatan']);
    Route::get('/wilayah/desa', [WilayahController::class, 'getDesa']);

    Route::get('/kartu-keluarga/{id}/anggota', [AnggotaKeluargaController::class, 'indexByKK'])->name('anggota-keluarga.index');
    Route::post('/anggota-keluarga', [AnggotaKeluargaController::class, 'store'])->name('anggota-keluarga.store');
    Route::put('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'update'])->name('anggota-keluarga.update');
    Route::delete('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota-keluarga.destroy');

    Route::middleware(['auth'])->group(function () {
        Route::get('/semua-warga', [WargaController::class, 'index'])->name('semua-warga.index');
    });
    Route::get('/warga/export', [WargaExportController::class, 'export'])->name('warga.export');
});

require __DIR__ . '/auth.php';
