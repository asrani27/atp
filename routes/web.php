<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\TppController;
use App\Http\Controllers\PajakController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 1. DOMAIN SPECIFIC ROUTING: ABSENSI (Option 1)
|--------------------------------------------------------------------------
*/
Route::domain(env('DOMAIN_ABSENSI', 'absensi.local'))->middleware(['auth'])->group(function () {
    Route::get('/', [PresensiController::class, 'index'])->name('domain.absensi.home');
    Route::post('/check-in', [PresensiController::class, 'checkIn'])->name('domain.absensi.checkin');
    Route::post('/check-out', [PresensiController::class, 'checkOut'])->name('domain.absensi.checkout');
    Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('domain.absensi.riwayat');
    Route::resource('jam-kerja', JamKerjaController::class, ['as' => 'domain.absensi']);
});

/*
|--------------------------------------------------------------------------
| 2. DOMAIN SPECIFIC ROUTING: TPP (Option 1)
|--------------------------------------------------------------------------
*/
Route::domain(env('DOMAIN_TPP', 'tpp.local'))->middleware(['auth'])->group(function () {
    Route::get('/', [TppController::class, 'index'])->name('domain.tpp.home');
    Route::get('/aktivitas', [TppController::class, 'aktivitas'])->name('domain.tpp.aktivitas');
    Route::post('/aktivitas', [TppController::class, 'storeAktivitas'])->name('domain.tpp.aktivitas.store');
    Route::post('/aktivitas/{aktivitas}/validate', [TppController::class, 'validateAktivitas'])->name('domain.tpp.aktivitas.validate');
    Route::get('/kalkulasi', [TppController::class, 'kalkulasi'])->name('domain.tpp.kalkulasi');
});

/*
|--------------------------------------------------------------------------
| 3. DOMAIN SPECIFIC ROUTING: PAJAK ASN (Option 1)
|--------------------------------------------------------------------------
*/
Route::domain(env('DOMAIN_PAJAK', 'pajakasn.local'))->middleware(['auth'])->group(function () {
    Route::get('/', [PajakController::class, 'index'])->name('domain.pajak.home');
    Route::get('/simulator', [PajakController::class, 'simulator'])->name('domain.pajak.simulator');
    Route::get('/tarif', [PajakController::class, 'tarif'])->name('domain.pajak.tarif');
});

/*
|--------------------------------------------------------------------------
| 4. UNIFIED PORTAL & LOCAL FALLBACK ROUTES (e.g. localhost:8000)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Portal Home
    Route::get('/', [DashboardController::class, 'index'])->name('portal.dashboard');

    // Modul Absensi & Jam Kerja
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [PresensiController::class, 'index'])->name('index');
        Route::post('/check-in', [PresensiController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [PresensiController::class, 'checkOut'])->name('checkout');
        Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');
    });

    // Panel Admin Jam Kerja Dinamis
    Route::resource('jam-kerja', JamKerjaController::class);

    // Modul TPP & Aktivitas
    Route::prefix('tpp')->name('tpp.')->group(function () {
        Route::get('/', [TppController::class, 'index'])->name('index');
        Route::get('/aktivitas', [TppController::class, 'aktivitas'])->name('aktivitas');
        Route::post('/aktivitas', [TppController::class, 'storeAktivitas'])->name('aktivitas.store');
        Route::post('/aktivitas/{aktivitas}/validate', [TppController::class, 'validateAktivitas'])->name('aktivitas.validate');
        Route::get('/kalkulasi', [TppController::class, 'kalkulasi'])->name('kalkulasi');
    });

    // Modul Pajak ASN & TER PP 58/2023
    Route::prefix('pajak')->name('pajak.')->group(function () {
        Route::get('/', [PajakController::class, 'index'])->name('index');
        Route::get('/simulator', [PajakController::class, 'simulator'])->name('simulator');
        Route::get('/tarif', [PajakController::class, 'tarif'])->name('tarif');
    });
});

