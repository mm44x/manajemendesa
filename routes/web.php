<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\AnggotaKeluargaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\WargaExportController;

Route::get('/', fn() => view('welcome'));

// Dashboard: langsung ke controller (supaya dinamis)
// routes/web.php
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


// Group route yang perlu auth
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Wilayah (AJAX)
    Route::prefix('wilayah')->group(function () {
        Route::get('/provinsi', [WilayahController::class, 'getProvinsi']);
        Route::get('/kabupaten', [WilayahController::class, 'getKabupaten']);
        Route::get('/kecamatan', [WilayahController::class, 'getKecamatan']);
        Route::get('/desa', [WilayahController::class, 'getDesa']);
    });

    // Kartu Keluarga - Admin & Sekretaris
    Route::middleware('role:admin,sekretaris')->group(function () {
        Route::resource('kartu-keluarga', KartuKeluargaController::class);
        Route::get('/warga/export', [WargaExportController::class, 'export'])->name('warga.export');
    });

    // Anggota Keluarga - Sekretaris
    Route::middleware('role:sekretaris')->group(function () {
        Route::post('/anggota-keluarga', [AnggotaKeluargaController::class, 'store'])->name('anggota-keluarga.store');
        Route::put('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'update'])->name('anggota-keluarga.update');
        Route::delete('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota-keluarga.destroy');
    });

    // Lihat anggota keluarga per KK
    Route::get('/kartu-keluarga/{id}/anggota', [AnggotaKeluargaController::class, 'indexByKK'])->name('anggota-keluarga.index');

    // Lihat semua warga
    Route::get('/semua-warga', [WargaController::class, 'index'])->name('semua-warga.index');

    // Iuran
    Route::resource('iuran', IuranController::class);
    Route::get('iuran/{iuran}/setoran', [IuranController::class, 'inputSetoran'])->name('iuran.setoran.input');
    Route::post('iuran/{iuran}/setoran', [IuranController::class, 'storeSetoran'])->name('iuran.setoran.store');
    Route::get('iuran/{iuran}/input-setoran-ajax', [IuranController::class, 'inputSetoranAjax'])->name('iuran.setoran.input.ajax');
});

require __DIR__ . '/auth.php';
