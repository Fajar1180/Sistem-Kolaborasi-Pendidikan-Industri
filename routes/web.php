<?php

use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserImportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mitra\ProfilMitraController;
use App\Http\Controllers\Mitra\ProgramMitraController;
use App\Http\Controllers\ProfileController;
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

    // Halaman akun sendiri — semua peran (FR-02).
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |----------------------------------------------------------------------
    | Area Admin — FR-14 (S1-06): pengguna & data master
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Impor CSV data awal.
        Route::get('import', [UserImportController::class, 'index'])->name('import.index');
        Route::post('import', [UserImportController::class, 'store'])->name('import.store');
        Route::get('import/template', [UserImportController::class, 'template'])->name('import.template');

        // Data master.
        Route::get('master/program-studi', [ProgramStudiController::class, 'index'])->name('master.program-studi.index');
        Route::post('master/program-studi', [ProgramStudiController::class, 'store'])->name('master.program-studi.store');
        Route::put('master/program-studi/{program_studi}', [ProgramStudiController::class, 'update'])->name('master.program-studi.update');
        Route::delete('master/program-studi/{program_studi}', [ProgramStudiController::class, 'destroy'])->name('master.program-studi.destroy');

        Route::get('master/periode-akademik', [PeriodeAkademikController::class, 'index'])->name('master.periode-akademik.index');
        Route::post('master/periode-akademik', [PeriodeAkademikController::class, 'store'])->name('master.periode-akademik.store');
        Route::put('master/periode-akademik/{periode_akademik}', [PeriodeAkademikController::class, 'update'])->name('master.periode-akademik.update');
        Route::delete('master/periode-akademik/{periode_akademik}', [PeriodeAkademikController::class, 'destroy'])->name('master.periode-akademik.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Area per peran lain — FR-02 (matriks hak akses PRD 7.7)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::view('pengajuan', 'placeholder', ['judul' => 'Persetujuan Pengajuan Penempatan', 'ref' => 'S2-01'])->name('pengajuan.index');
    });

    Route::middleware('role:mitra')->prefix('mitra')->name('mitra.')->group(function () {
        // Profil perusahaan milik sendiri (FR-11).
        Route::get('profil', [ProfilMitraController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ProfilMitraController::class, 'update'])->name('profil.update');

        // Peluang program milik sendiri (FR-11).
        Route::get('program', [ProgramMitraController::class, 'index'])->name('program.index');
        Route::get('program/create', [ProgramMitraController::class, 'create'])->name('program.create');
        Route::post('program', [ProgramMitraController::class, 'store'])->name('program.store');
        Route::get('program/{program}/edit', [ProgramMitraController::class, 'edit'])->name('program.edit');
        Route::put('program/{program}', [ProgramMitraController::class, 'update'])->name('program.update');
        Route::patch('program/{program}/publish', [ProgramMitraController::class, 'publish'])->name('program.publish');
        Route::patch('program/{program}/close', [ProgramMitraController::class, 'close'])->name('program.close');
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::view('program', 'placeholder', ['judul' => 'Peluang Program & Pendaftaran', 'ref' => 'S1-08'])->name('program.index');
    });
});
