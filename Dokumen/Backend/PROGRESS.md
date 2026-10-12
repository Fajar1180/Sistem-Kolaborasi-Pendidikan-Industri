# Progress Dokumentasi — Backend

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri
**Tim:** Backend Developer (Ilham Almunawar)
**Sprint:** Sprint 1 (Minggu 6-7) / Sprint 2 (Minggu 8-9)

---

## Format Penulisan Progress

Setiap entri progress harus berisi:

- **Tanggal** (YYYY-MM-DD)
- **ID Pekerjaan** (sesuai Sprint Backlog: S1-01 sampai S1-10 / S2-01 sampai S2-10)
- **Judul Pekerjaan** (sesuai Sprint Backlog)
- **Cabang Git** (branch tempat pengerjaan, mis. `feature/auth`)
- **Komitmen yang telah dilakukan** (poin-poin)
- **Status** (`Belum mulai` / `Sedang mengerjakan` / `Selesai - ready for review` / `Blocker`)
- **Catatan** (kendala, hal yang perlu diskusi, link PR/issue terkait)

---

## Sprint 1

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S1-01 | Penyiapan repositori, lingkungan pengembangan, dan struktur proyek | `chore/project-setup` | Selesai - ready for review | Scaffold Laravel 13, `.env.example` MySQL Laragon, `.gitignore`, README setup. Issue #1 |
| S1-02 | Skema basis data dan migrasi awal | `feature/database-schema` | Selesai - ready for review | 14 tabel + model Eloquent + relasi + factory + seeder. Issue #2 |
| S1-03 | Autentikasi: masuk, keluar, dan atur ulang kata sandi | `feature/auth-roles` | Selesai - ready for review | Login/logout + lupa/atur ulang kata sandi + rate limit + cek akun aktif. Issue #3 |
| S1-04 | Hak akses berdasarkan empat peran | `feature/auth-roles` | Selesai - ready for review | Middleware `role` + `active`, area per peran, dashboard per peran. Issue #4 |
| S1-05 | Tata letak responsif dan komponen antarmuka dasar | | (Frontend) | Issue #5 |
| S1-06 | Admin: pengelolaan pengguna dan data master | | Belum mulai | Issue #6 |
| S1-07 | Mitra: profil dan publikasi peluang program | | Belum mulai | Issue #7 |
| S1-08 | Mahasiswa: melihat peluang dan mendaftar program | | Belum mulai | Issue #8 |
| S1-09 | Mahasiswa: pengajuan penempatan | | Belum mulai | Issue #9 |
| S1-10 | Uji fungsi Sprint 1 dan dokumentasi | | (QA) | Issue #10 |

---

## Sprint 2

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S2-01 | Dosen: persetujuan pengajuan penempatan | | Belum mulai | Issue #11 |
| S2-02 | Mitra: seleksi dan verifikasi peserta | | Belum mulai | Issue #12 |
| S2-03 | Mahasiswa: pencatatan kegiatan | | Belum mulai | Issue #13 |
| S2-04 | Mahasiswa: pengelolaan dokumen dan pengumpulan laporan | | Belum mulai | Issue #15 |
| S2-05 | Dosen: pemantauan, pembimbingan, dan evaluasi | | Belum mulai | Issue #16 |
| S2-06 | Mitra: penilaian mahasiswa | | Belum mulai | Issue #17 |
| S2-07 | Notifikasi dalam sistem dan email | | Belum mulai | Issue #18 |
| S2-08 | Admin: pengawasan program, rekapitulasi, dan ekspor laporan | | Belum mulai | Issue #19 |
| S2-09 | Integrasi antarmodul dan perbaikan temuan Sprint 1 | | Belum mulai | Issue #20 |
| S2-10 | Uji fungsi Sprint 2 dan dokumentasi | | (QA) | Issue #21 |

---

## Catatan Harian (Log Detail)

## 2026-10-12

- **[S1-01] Penyiapan repositori, lingkungan pengembangan, dan struktur proyek** — Cabang: `chore/project-setup`
  - Status: Selesai - ready for review
  - Progress:
    - Inisialisasi proyek Laravel 13 di root repo (`composer create-project laravel/laravel`).
    - `.env.example` diarahkan ke MySQL Laragon (`DB_CONNECTION=mysql`, `DB_DATABASE=skp_db`), `APP_NAME` dan locale `id`.
    - `.gitignore` diverifikasi & diperketat: `.env`, `/vendor`, `/node_modules`, cache, dan file editor/AI tidak ikut commit.
    - `README.md`: langkah setup lokal, setup MySQL Laragon, dan daftar akun hasil seeder.
    - Branch `develop` dibuat sebagai branch integrasi pengembangan (`main` hanya rilis via PR PM).
  - Catatan: PR menuju `develop` (`Closes #1`). Terverifikasi: `php artisan key:generate` + `php artisan migrate --seed` berhasil.

- **[S1-02] Skema basis data dan migrasi awal** — Cabang: `feature/database-schema`
  - Status: Selesai - ready for review
  - Progress:
    - Migrasi `users` ditambah kolom `role` (mahasiswa|dosen|mitra|admin) + `is_active`.
    - Tabel domain: `mahasiswas`, `dosens`, `mitras`, `programs`, `pendaftarans`, `kegiatans`, `dokumens`, `laporans`, `bimbingans`, `penilaian_dosens`, `penilaian_mitras`, `notifikasis`, `activity_logs`.
    - Relasi Eloquent (hasMany/belongsTo) lengkap untuk seluruh model; status `pendaftarans` mengikuti PRD §7.5.
    - Factory untuk semua model + `DatabaseSeeder` (1 admin, 2 dosen, 5 mahasiswa, 2 mitra, 3 program published + 1 draft, 8 pengajuan contoh tiap status).
    - Kuota program dijaga tidak minus (`unsignedInteger` + helper `sisaKuota()`).
  - Catatan: `php artisan migrate:fresh --seed` sukses dari nol; relasi diverifikasi via tinker. PR menuju `develop` (`Closes #2`).

- **[S1-03] Autentikasi: masuk, keluar, dan atur ulang kata sandi** — Cabang: `feature/auth-roles`
  - Status: Selesai - ready for review
  - Progress:
    - Controller `AuthenticatedSessionController` (masuk, keluar), `PasswordResetLinkController` (lupa kata sandi), `NewPasswordController` (atur ulang).
    - `LoginRequest` dengan validasi + rate limit 5 percobaan + cek `is_active` (akun nonaktif tidak bisa masuk).
    - Kata sandi disimpan ter-hash (`password` => `hashed` pada model User).
    - View `auth/login`, `auth/forgot-password`, `auth/reset-password` (Tailwind, responsif) + layout guest.
  - Catatan: 3 feature test autentikasi & reset kata sandi lulus. PR `Closes #3`.

- **[S1-04] Hak akses berdasarkan empat peran** — Cabang: `feature/auth-roles`
  - Status: Selesai - ready for review
  - Progress:
    - Middleware `EnsureUserHasRole` (`role:admin,dosen,mitra,mahasiswa`) → 403 bila peran tidak berwenang.
    - Middleware `EnsureUserIsActive` (`active`) → akun nonaktif dikeluarkan & diarahkan ke login.
    - Dashboard menyesuaikan peran; rute contoh area tiap peran (`/admin/*`, `/dosen/*`, `/mitra/*`, `/mahasiswa/*`) siap diisi modul berikutnya.
    - Redirect tamu ke `login`, pengguna yang sudah masuk ke `dashboard`.
  - Catatan: 7 feature test hak akses lulus (`php artisan test`: 18 passed). PR `Closes #4`.
