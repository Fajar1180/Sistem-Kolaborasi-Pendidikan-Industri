<?php

use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserImportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredMitraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Dosen\BimbinganController;
use App\Http\Controllers\Dosen\PenilaianController;
use App\Http\Controllers\Dosen\PengajuanDosenController;
use App\Http\Controllers\Mahasiswa\KegiatanController;
use App\Http\Controllers\Mahasiswa\DokumenMahasiswaController;
use App\Http\Controllers\Mahasiswa\LaporanController;
use App\Http\Controllers\Mahasiswa\PendaftaranMahasiswaController;
use App\Http\Controllers\Mahasiswa\PengajuanController;
use App\Http\Controllers\Mahasiswa\ProgramKatalogController;
use App\Http\Controllers\Mitra\ProfilMitraController;
use App\Http\Controllers\Mitra\PenilaianMitraController;
use App\Http\Controllers\Mitra\ProgramMitraController;
use App\Http\Controllers\Mitra\SeleksiMitraController;
use App\Http\Controllers\Mahasiswa\KomentarController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');

/*
|--------------------------------------------------------------------------
| Rute tamu (belum masuk) — FR-01
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Redirect /register ke login dengan info (issue #44)
    Route::get('register', fn() => redirect()->route('login')->with('info', 'Akun dibuat oleh admin. Silakan hubungi administrator untuk mendapatkan akses.'))->name('register');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // Registrasi mitra industri (FR-11) — #44
    Route::get('register-mitra', [RegisteredMitraController::class, 'create'])->name('register-mitra');
    Route::post('register-mitra', [RegisteredMitraController::class, 'store']);
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

    // Unduh berkas pengajuan dari storage privat (NFR-08) — S1-09.
    Route::get('dokumen/{dokumen}', [DokumenController::class, 'unduh'])->name('dokumen.unduh');

    // Arsip notifikasi + penanda baca (FR-17) — S2-07b.
    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/baca-semua', [NotifikasiController::class, 'tandaiSemua'])->name('notifikasi.baca-semua');
    Route::post('notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.baca');

    // Diskusi komentar per pendaftaran (FR-17) — S2-07a.
    Route::get('pendaftaran/{pendaftaran}/komentar', [KomentarController::class, 'index'])->name('komentar.index');
    Route::post('pendaftaran/{pendaftaran}/komentar', [KomentarController::class, 'store'])->name('komentar.store');

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

        // Pengawasan seluruh pengajuan penempatan (hanya-baca) — S2-01/S2-08.
        Route::get('pengajuan', [PengajuanDosenController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/{pendaftaran}', [PengajuanDosenController::class, 'show'])->name('pengajuan.show');

        // Rekap dan ekspor laporan (FR-15, FR-16) — S2-08.
        Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('rekap/program', [RekapController::class, 'perProgram'])->name('rekap.program');
        Route::get('rekap/prodi', [RekapController::class, 'perProdi'])->name('rekap.prodi');
        Route::get('rekap/mitra', [RekapController::class, 'perMitra'])->name('rekap.mitra');
        Route::get('rekap/ekspor-excel', [RekapController::class, 'eksporExcel'])->name('rekap.ekspor-excel');
    });

    /*
    |----------------------------------------------------------------------
    | Area per peran lain — FR-02 (matriks hak akses PRD 7.7)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        // Persetujuan pengajuan penempatan (FR-08) — S2-01.
        Route::get('pengajuan', [PengajuanDosenController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/{pendaftaran}', [PengajuanDosenController::class, 'show'])->name('pengajuan.show');
        Route::post('pengajuan/{pendaftaran}/setujui', [PengajuanDosenController::class, 'setujui'])->name('pengajuan.setujui');
        Route::post('pengajuan/{pendaftaran}/tolak', [PengajuanDosenController::class, 'tolak'])->name('pengajuan.tolak');
        Route::post('pengajuan/{pendaftaran}/revisi', [PengajuanDosenController::class, 'revisi'])->name('pengajuan.revisi');

        // Bimbingan mahasiswa (FR-09) — S2-05.
        Route::get('bimbingan', [BimbinganController::class, 'index'])->name('bimbingan.index');
        Route::get('bimbingan/{pendaftaran}', [BimbinganController::class, 'show'])->name('bimbingan.show');
        Route::post('bimbingan/{pendaftaran}', [BimbinganController::class, 'store'])->name('bimbingan.store');
        Route::put('bimbingan/{pendaftaran}/{bimbingan}', [BimbinganController::class, 'update'])->name('bimbingan.update');
        Route::delete('bimbingan/{pendaftaran}/{bimbingan}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy');

        // Penilaian rubrik + finalisasi (FR-10) — S2-05.
        Route::get('penilaian/{pendaftaran}', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::put('penilaian/{pendaftaran}', [PenilaianController::class, 'update'])->name('penilaian.update');
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

        // Seleksi & verifikasi pelamar (FR-12) — S2-02.
        Route::get('program/{program}/pelamar', [SeleksiMitraController::class, 'index'])->name('program.pelamar');
        Route::post('pelamar/{pendaftaran}/terima', [SeleksiMitraController::class, 'terima'])->name('pelamar.terima');
        Route::post('pelamar/{pendaftaran}/tolak', [SeleksiMitraController::class, 'tolak'])->name('pelamar.tolak');

        // Penilaian peserta (FR-11) — S2-06. Rubrik bersama, final terkunci.
        Route::get('penilaian', [PenilaianMitraController::class, 'index'])->name('penilaian.index');
        Route::get('penilaian/{pendaftaran}', [PenilaianMitraController::class, 'edit'])->name('penilaian.edit');
        Route::put('penilaian/{pendaftaran}', [PenilaianMitraController::class, 'update'])->name('penilaian.update');
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // Katalog peluang program (FR-03) — S1-08.
        Route::get('program', [ProgramKatalogController::class, 'index'])->name('program.index');
        Route::get('program/{program}', [ProgramKatalogController::class, 'show'])->name('program.show');

        // Pendaftaran program (FR-03) — S1-08.
        Route::get('program/{program}/daftar', [PendaftaranMahasiswaController::class, 'create'])->name('pendaftaran.create');
        Route::post('program/{program}/daftar', [PendaftaranMahasiswaController::class, 'store'])->name('pendaftaran.store');

        // Pendaftaran Saya (FR-03) — S1-08.
        Route::get('pendaftaran', [PendaftaranMahasiswaController::class, 'index'])->name('pendaftaran.index');
        Route::get('pendaftaran/{pendaftaran}', [PendaftaranMahasiswaController::class, 'show'])->name('pendaftaran.show');
        Route::delete('pendaftaran/{pendaftaran}', [PendaftaranMahasiswaController::class, 'destroy'])->name('pendaftaran.destroy');

        // Pengajuan penempatan: isi formulir + lampiran berkas (FR-04) — S1-09.
        Route::get('pendaftaran/{pendaftaran}/pengajuan', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('pendaftaran/{pendaftaran}/pengajuan', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::post('pendaftaran/{pendaftaran}/ajukan', [PengajuanController::class, 'ajukan'])->name('pengajuan.ajukan');

        // Pencatatan kegiatan (FR-05) — S2-03
        Route::get('pendaftaran/{pendaftaran}/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('pendaftaran/{pendaftaran}/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('pendaftaran/{pendaftaran}/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('pendaftaran/{pendaftaran}/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('pendaftaran/{pendaftaran}/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('pendaftaran/{pendaftaran}/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

        // Kelola dokumen saat berjalan (FR-06) — S2-04.
        Route::get('pendaftaran/{pendaftaran}/dokumen', [DokumenMahasiswaController::class, 'index'])->name('dokumen.index');
        Route::post('pendaftaran/{pendaftaran}/dokumen', [DokumenMahasiswaController::class, 'store'])->name('dokumen.store');
        Route::put('pendaftaran/{pendaftaran}/dokumen/{dokumen}', [DokumenMahasiswaController::class, 'update'])->name('dokumen.update');
        Route::delete('pendaftaran/{pendaftaran}/dokumen/{dokumen}', [DokumenMahasiswaController::class, 'destroy'])->name('dokumen.destroy');

        // Laporan final + tenggat (FR-07) — S2-04.
        Route::get('pendaftaran/{pendaftaran}/laporan', [LaporanController::class, 'show'])->name('laporan.show');
        Route::post('pendaftaran/{pendaftaran}/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::delete('pendaftaran/{pendaftaran}/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    });
});
