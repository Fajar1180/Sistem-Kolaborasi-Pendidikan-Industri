# Laporan Uji Fungsi — Sprint 1

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Sprint:** Sprint 1 (Minggu 6–7)
**Build yang diuji:** cabang `docs/uji-sprint-1` (integrasi `main` + `feature/mahasiswa-daftar`)
**Commit:** `11086ed` — *merge: integrasi main + S1-08/S1-09 untuk pengujian Sprint 1*
**Tanggal uji:** 2026-10-12
**Penguji:** QA (Fazna) — dibantu System Analyst & Backend

---

## 1. Ringkasan Eksekutif

- Build integrasi Sprint 1 (S1-01…S1-09) berhasil dirakit dan **lulus seluruh uji otomatis: 110 test, 374 assertion, 0 gagal**.
- Cakupan skenario fungsional A–F (FR-01…FR-04, FR-11, FR-14) **64 dari 64 skenario otomatis berstatus Lulus**.
- Tiga skenario non-fungsional (responsif 360px, desktop, lintas browser) **menunggu eksekusi manual di perangkat/browser asli**.
- **Tidak ditemukan bug** pada build integrasi.

## 2. Lingkungan Uji

| Komponen | Nilai |
|----------|-------|
| Sistem operasi | Windows (Laragon) |
| PHP | 8.3.30 (Laragon) |
| Framework | Laravel 13 |
| Basis data | MySQL 8 — `skp_db` |
| Alat uji | PHPUnit via `php artisan test` |
| Isolasi DB uji | `RefreshDatabase` (migrasi + seed ulang tiap test) |

## 3. Hasil Uji Otomatis

Perintah:

```bash
php artisan test
```

Ringkasan keluaran: **`tests: 110, passed: 110, assertions: 374`** (±12 detik).

| Berkas uji | Jumlah | Cakupan (Sprint Backlog) |
|------------|-------:|--------------------------|
| `Feature/Auth/AuthenticationTest.php` | 8 | S1-03 masuk/keluar |
| `Feature/Auth/PasswordResetTest.php` | 4 | S1-03 lupa/atur ulang sandi |
| `Feature/AccessMatrixTest.php` | 5 | S1-04 matriks akses |
| `Feature/RoleAccessTest.php` | 7 | S1-04 redirect & batas peran |
| `Feature/ProfileTest.php` | 6 | S1-04 Akun Saya |
| `Feature/Admin/UserManagementTest.php` | 13 | S1-06 pengguna (FR-14) |
| `Feature/Admin/UserImportTest.php` | 6 | S1-06 impor CSV |
| `Feature/Admin/MasterDataTest.php` | 8 | S1-06 program studi & periode |
| `Feature/Mitra/ProfilMitraTest.php` | 8 | S1-07 profil mitra |
| `Feature/Mitra/ProgramMitraTest.php` | 11 | S1-07 peluang program |
| `Feature/Mahasiswa/ProgramKatalogTest.php` | 8 | S1-08 katalog & filter |
| `Feature/Mahasiswa/PendaftaranMahasiswaTest.php` | 10 | S1-08 daftar/batalkan |
| `Feature/Mahasiswa/PengajuanTest.php` | 14 | S1-09 pengajuan & berkas privat |
| `Feature/ExampleTest.php` + `Unit/ExampleTest.php` | 2 | Smoke default Laravel |
| **Total** | **110** | (108 uji fitur + 2 smoke) |

## 4. Pemetaan Hasil terhadap Skenario

| Kelompok skenario | Jumlah | Lulus | Belum diuji |
|-------------------|-------:|------:|------------:|
| A. Autentikasi (FR-01) | 10 | 10 | 0 |
| B. Hak akses (FR-02) | 8 | 8 | 0 |
| C. Admin (FR-14) | 12 | 12 | 0 |
| D. Mitra (FR-11) | 9 | 9 | 0 |
| E. Mahasiswa katalog/daftar (FR-03) | 12 | 12 | 0 |
| F. Mahasiswa pengajuan (FR-04) | 10 | 10 | 0 |
| G. Non-fungsional (NFR) | 6 | 3 | 3 |
| **Total** | **67** | **64** | **3** |

Rincian tiap skenario (langkah & hasil harapan) ada di `Skenario-Uji-Sprint-1.md`.

## 5. Catatan Integrasi

Integrasi `main` (S1-01…S1-07) dengan `feature/mahasiswa-daftar` (S1-08 & S1-09) menimbulkan konflik teks pada tiga berkas yang diselesaikan tanpa perubahan perilaku:

| Berkas | Sifat konflik | Resolusi |
|--------|---------------|----------|
| `routes/web.php` | Blok `use` controller mitra vs mahasiswa berdekatan | Gabungkan kedua kelompok `use` |
| `lang/id/validation.php` | Atribut validasi berbeda (program vs pengajuan) | Gabungkan seluruh atribut |
| `Dokumen/Backend/PROGRESS.md` | Baris & detail S1-07 vs S1-08/S1-09 | Gabungkan baris S1-07 + S1-08 + S1-09 |

Setelah resolusi, seluruh suite lulus (110/110) — tidak ada regresi lintas modul.

## 6. Temuan (Bug)

**Tidak ada.** Tidak ditemukan cacat fungsional pada build integrasi. Bila pada uji manual perangkat ditemukan masalah, catat sebagai issue baru berlabel `bug` + `sprint-1` (bukan ditumpuk di issue #10).

## 7. Belum Dijalankan (Tindak Lanjut QA)

| ID | Skenario | Alasan |
|----|----------|--------|
| TC-NFR-01 | Responsif HP (360px) | Butuh perangkat/emulator nyata |
| TC-NFR-02 | Responsif desktop | Butuh browser nyata |
| TC-NFR-03 | Lintas browser (Chrome + 1) | Butuh ≥ 2 browser nyata |

## 8. Kesimpulan & Rekomendasi

- **Kesimpulan:** Build integrasi Sprint 1 memenuhi kriteria fungsional utama; seluruh skenario otomatis (64/64) lulus. Kesiapan rilis **bergantung pada** penyelesaian 3 skenario manual perangkat/browser (kriteria DoD §6.3: *"tampil baik di HP dan desktop"*).
- **Rekomendasi:**
  1. QA menjalankan TC-NFR-01…03 di HP + desktop dan memperbarui status di `Skenario-Uji-Sprint-1.md`.
  2. Bila bersih, tandai S1-10 `Selesai - ready for review` dan siap masuk sprint review.
  3. Temuan manual (bila ada) dicatat sebagai issue `bug` + `sprint-1` untuk ditangani di S2-09 (perbaikan temuan Sprint 1).

## 9. Cara Menjalankan Ulang

```bash
# 1. Ambil build uji
git fetch origin
git checkout docs/uji-sprint-1

# 2. Siapkan lingkungan
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# 3. Jalankan seluruh uji otomatis
php artisan test
```
