<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\AnggotaKeluargaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\WargaExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Wilayah (akses umum, AJAX)
    Route::get('/wilayah/provinsi', [WilayahController::class, 'getProvinsi']);
    Route::get('/wilayah/kabupaten', [WilayahController::class, 'getKabupaten']);
    Route::get('/wilayah/kecamatan', [WilayahController::class, 'getKecamatan']);
    Route::get('/wilayah/desa', [WilayahController::class, 'getDesa']);

    // 📁 Modul Kartu Keluarga - Hanya Admin dan Sekretaris
    Route::middleware(['role:admin,sekretaris'])->group(function () {
        Route::resource('kartu-keluarga', KartuKeluargaController::class);
    });

    // 👨‍👩‍👧 Modul Anggota Keluarga - Hanya Sekretaris
    Route::middleware(['role:sekretaris'])->group(function () {
        Route::post('/anggota-keluarga', [AnggotaKeluargaController::class, 'store'])->name('anggota-keluarga.store');
        Route::put('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'update'])->name('anggota-keluarga.update');
        Route::delete('/anggota-keluarga/{id}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota-keluarga.destroy');
    });

    // 👁️ Lihat Anggota Keluarga per KK - Semua Role
    Route::get('/kartu-keluarga/{id}/anggota', [AnggotaKeluargaController::class, 'indexByKK'])->name('anggota-keluarga.index');

    // 📄 Lihat Semua Warga - Semua Role
    Route::get('/semua-warga', [WargaController::class, 'index'])->name('semua-warga.index');

    // 📤 Export Excel - Hanya Admin dan Sekretaris
    Route::middleware(['role:admin,sekretaris'])->group(function () {
        Route::get('/warga/export', [WargaExportController::class, 'export'])->name('warga.export');
    });

    Route::resource('iuran', IuranController::class)->middleware('auth');
});

require __DIR__ . '/auth.php';
