<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Rute tamu (belum masuk) — FR-01
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Rute wajib masuk (akun harus aktif) — FR-01 & FR-02
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Area per peran — FR-02 (matriks hak akses PRD 7.7)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('users', 'placeholder', ['judul' => 'Pengelolaan Pengguna & Data Master', 'ref' => 'S1-06'])->name('users.index');
    });

    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::view('pengajuan', 'placeholder', ['judul' => 'Persetujuan Pengajuan Penempatan', 'ref' => 'S2-01'])->name('pengajuan.index');
    });

    Route::middleware('role:mitra')->prefix('mitra')->name('mitra.')->group(function () {
        Route::view('program', 'placeholder', ['judul' => 'Profil & Peluang Program Mitra', 'ref' => 'S1-07'])->name('program.index');
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::view('program', 'placeholder', ['judul' => 'Peluang Program & Pendaftaran', 'ref' => 'S1-08'])->name('program.index');
    });
});
