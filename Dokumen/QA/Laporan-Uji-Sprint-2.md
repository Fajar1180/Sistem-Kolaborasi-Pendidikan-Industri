# Laporan Uji Fungsi — Sprint 2

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Sprint:** Sprint 2 (Minggu 8–9)
**Build yang diuji:** cabang `docs/qa-sprint-2` (integrasi `main` = S1-01…S1-10 + S2-01…S2-09)
**Commit:** e7072af — *merge PR #42 feature/ui-mintlify (terakhir di main saat pull)*
**Tanggal uji:** 2026-10-16
**Penguji:** QA (ilhamalmunawar05-cpu) — dibantu System Analyst & Backend

---

## 1. Ringkasan Eksekutif

- Build integrasi Sprint 2 (S2-01…S2-09) berhasil dirakit dan **lulus seluruh uji otomatis: 175 test, 555 assertion, 0 gagal**.
- Regresi dari S2-09 (mengubah `withErrors`→`with('error')` pada controller admin) ditemukan dan diperbaiki kembali ke `withErrors` agar `assertSessionHasErrors` di tes berlaku.
- Cakupan skenario fungsional A–K **57 dari 57 skenario otomatis berstatus Lulus**.
- Tiga skenario non-fungsional (responsif 360px, desktop, lintas browser) **menunggu eksekusi manual di perangkat/browser asli**.
- **Tidak ditemukan bug baru** pada build integrasi.

---

## 2. Lingkungan Uji

| Komponen | Nilai |
|----------|-------|
| Sistem operasi | Windows (Laragon) |
| PHP | 8.3.30 (Laragon) |
| Framework | Laravel |
| Basis data | MySQL 8 — `skp_db` |
| Alat uji | PHPUnit via `php artisan test` |
| Isolasi DB uji | `RefreshDatabase` (migrasi + seed ulang tiap test) |

---

## 3. Hasil Uji Otomatis

Perintah:

```bash
php artisan test
```

Ringkasan keluaran: **`tests: 175, passed: 175, assertions: 555`** (±17 detik).

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
| `Feature/Admin/RekapTest.php` | 5 | S2-08 rekapitulasi |
| `Feature/Mitra/ProfilMitraTest.php` | 8 | S1-07 profil mitra |
| `Feature/Mitra/ProgramMitraTest.php` | 11 | S1-07 peluang program |
| `Feature/Mitra/SeleksiMitraTest.php` | 13 | S2-02 seleksi peserta |
| `Feature/Mitra/PenilaianMitraTest.php` | 6 | S2-06 penilaian mitra |
| `Feature/Dosen/PersetujuanPengajuanTest.php` | 13 | S2-01 persetujuan dosen |
| `Feature/Dosen/BimbinganPenilaianTest.php` | 7 | S2-05 bimbingan & evaluasi |
| `Feature/Mahasiswa/ProgramKatalogTest.php` | 8 | S1-08 katalog & filter |
| `Feature/Mahasiswa/PendaftaranMahasiswaTest.php` | 10 | S1-08 daftar/batalkan |
| `Feature/Mahasiswa/PengajuanTest.php` | 14 | S1-09 pengajuan & berkas privat |
| `Feature/Mahasiswa/KegiatanTest.php` | 5 | S2-03 pencatatan kegiatan |
| `Feature/Mahasiswa/DokumenLaporanTest.php` | 7 | S2-04 dokumen & laporan |
| `Feature/Notifikasi/NotifikasiKomentarTest.php` | 9 | S2-07 notifikasi & komentar |
| `Feature/ExampleTest.php` + `Unit/ExampleTest.php` | 2 | Smoke default Laravel |
| **Total** | **175** | (173 uji fitur + 2 smoke) |

---

## 4. Pemetaan Hasil terhadap Skenario

| Kelompok skenario | Jumlah | Lulus | Belum diuji |
|-------------------|-------:|------:|------------:|
| A. Autentikasi (FR-01) | 7 | 7 | 0 |
| B. Hak akses (FR-02) | 8 | 8 | 0 |
| C. Admin (FR-14) | 6 | 6 | 0 |
| D. Dosen persetujuan (S2-01) | 8 | 8 | 0 |
| E. Mitra seleksi (S2-02) | 8 | 8 | 0 |
| F. Mahasiswa kegiatan (S2-03) | 4 | 4 | 0 |
| G. Mahasiswa dokumen/laporan (S2-04) | 6 | 6 | 0 |
| H. Dosen bimbingan/penilaian (S2-05) | 4 | 4 | 0 |
| I. Mitra penilaian (S2-06) | 3 | 3 | 0 |
| J. Notifikasi (S2-07) | 4 | 4 | 0 |
| K. Integrasi (S2-09) | 2 | 2 | 0 |
| L. Non-fungsional (NFR) | 6 | 3 | 3 |
| **Total** | **60** | **57** | **3** |

Rincian tiap skenario (langkah & hasil harapan) ada di `Skenario-Uji-Sprint-2.md`.

---

## 5. Catatan Integrasi

Build ini adalah integrasi `main` yang sudah mencakup semua PR S2-01…S2-09 yang di-merge. Tidak ditemukan konflik blok yang perlu risolusi tambahan — `main` bersih.

Temuan utama selama QA:
- **Regresi S2-09**: `UserImportController`, `UserController::toggleActive`, `UserController::destroy` diubah dari `withErrors()` ke `with('error')`, sedangkan tes masih pakai `assertSessionHasErrors()`. Akibat 3 test gagal. **Perbalikan ke `withErrors(['key' => 'pesan'])`** agar tes dan layout (`$errors->any()`) tetap berfungsi. Pesan tetap tampil di layout melalui blok `$errors->any()`.

Setelah perbaikan, seluruh suite lulus (175/175) — tidak ada regresi lintas modul.

---

## 6. Temuan (Bug)

**Tidak ada bug baru.** Tidak ditemukan cacat fungsional pada build integrasi setelah perbaikan regresi di atas. Bila pada uji manual perangkat ditemukan masalah, catat sebagai issue baru berlabel `bug` + `sprint-2` (bukan ditumpuk di issue #21).

---

## 7. Belum Dijalankan (Tindak Lanjut QA)

| ID | Skenario | Alasan |
|----|----------|--------|
| TC-NFR-01 | Responsif HP (360px) | Butuh perangkat/emulator nyata |
| TC-NFR-02 | Responsif desktop | Butuh browser nyata |
| TC-NFR-03 | Lintas browser (Chrome + 1) | Butuh ≥ 2 browser nyata |

---

## 8. Kesimpulan & Rekomendasi

- **Kesimpulan:** Build integrasi Sprint 2 memenuhi kriteria fungsional utama; seluruh skenario otomatis (57/57) lulus. Kesiapan rilis bergantung pada penyelesaian 3 skenario manual perangkat/browser (kriteria DoD §6.3: *"tampil baik di HP dan desktop"*).
- **Rekomendasi:**
  1. QA menjalankan TC-NFR-01…03 di HP + desktop dan memperbarui status di `Skenario-Uji-Sprint-2.md`.
  2. Bila bersih, tandai S2-10 `Selesai - ready for review` dan siap masuk sprint review.
  3. Temuan manual (bila ada) dicatat sebagai issue `bug` + `sprint-2`.

---

## 9. Cara Menjalankan Ulang

```bash
# 1. Ambil build uji
git fetch origin
git checkout docs/qa-sprint-2

# 2. Siapkan lingkungan
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# 3. Jalankan seluruh uji otomatis
php artisan test
```
