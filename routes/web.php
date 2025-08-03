<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\WilayahController;


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
    // Route::resource('kartu-keluarga', KartuKeluargaController::class)->only(['index', 'create']);
    Route::resource('kartu-keluarga', KartuKeluargaController::class)->only(['index', 'create', 'store']);
});

Route::get('/wilayah/provinsi', [WilayahController::class, 'getProvinsi']);
Route::get('/wilayah/kabupaten', [WilayahController::class, 'getKabupaten']);
Route::get('/wilayah/kecamatan', [WilayahController::class, 'getKecamatan']);
Route::get('/wilayah/desa', [WilayahController::class, 'getDesa']);


});

require __DIR__.'/auth.php';
