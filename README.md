# Sistem Informasi Kolaborasi Pendidikan dan Industri

Sistem informasi berbasis web untuk mengelola kolaborasi pendidikan dan industri — menghubungkan **mahasiswa, dosen, mitra industri, dan admin** dalam satu alur: pendaftaran program, pengajuan penempatan, persetujuan, pemantauan kegiatan, penilaian, hingga rekapitulasi dan pelaporan.

**Kelompok 6 · TA. 2026**

## Deskripsi (dari Project Charter)

Pengelolaan program mahasiswa (kerja praktik, magang, proyek kolaborasi, kunjungan industri, sertifikasi) masih manual dan tersebar di berbagai media, sehingga persetujuan lambat, data peserta dan mitra tidak terintegrasi, dan pemantauan capaian kurang akurat. Sistem ini memusatkan seluruh proses tersebut dalam satu aplikasi web yang transparan dan terdokumentasi.

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP Laravel + MySQL |
| Frontend | Blade / Tailwind CSS (responsif) |
| Server lokal | Laragon |
| Version control | Git + GitHub (branch `develop` → `main`) |

> Keputusan teknologi final ditetapkan pada paket kerja 3.1 (Arsitektur Sistem).

## Setup untuk Programmer Baru

### Prasyarat

- [Laragon](https://laragon.org/) (Apache/Nginx + PHP 8.x + MySQL 8.x)
- PHP >= 8.2, Composer
- Node.js 18+ & npm (untuk aset frontend)
- Git + akun GitHub tim
- VS Code

### Instalasi

```bash
# 1. Clone repo
git clone https://github.com/Fajar1180/Sistem-Kolaborasi-Pendidikan-Industri.git
cd Sistem-Kolaborasi-Pendidikan-Industri

# 2. Install dependensi (struktur Laravel sudah ada di repo)
composer install
npm install

# 3. Siapkan environment lokal
cp .env.example .env
php artisan key:generate
# lalu sesuaikan kredensial database di .env (MySQL Laragon)

# 4. Migrasi + seeder data dummy (mahasiswa, dosen, admin, mitra)
php artisan migrate --seed

# 5. Jalankan
php artisan serve
```

> **Penting:** setiap programmer WAJIB menjalankan `php artisan migrate --seed` setelah `git pull` agar mendapat data sampel terbaru untuk testing — tidak perlu input manual.

### Setup MySQL di Laragon

1. Jalankan Laragon → **Start All** (Apache/Nginx + MySQL).
2. Buat database lokal (default proyek: `skp_db`):
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS skp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
3. Samakan nilai berikut di `.env` (lihat juga `.env.example`):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=skp_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Pastikan `php artisan migrate --seed` berhasil. Untuk mengulang dari nol: `php artisan migrate:fresh --seed`.

### Akun Hasil Seeder (password semua: `password`)

| Peran | Email |
|---|---|
| Admin | `admin@skp.test` |
| Dosen | `dosen1@skp.test`, `dosen2@skp.test` |
| Mahasiswa | `mhs1@skp.test` … `mhs5@skp.test` |
| Mitra | `mitra1@skp.test`, `mitra2@skp.test` |


## Struktur Dokumen

```
├── Dokumen Perencanaan Proyek … (Kelompok 6).md   # KAK, Charter, WBS, Jadwal, Sprint Backlog, PRD
├── RULES.md                                       # Aturan kerja tim (branch, PR, dokumentasi, AI)
├── Dokumen/
│   ├── Frontend/PROGRESS.md                       # Progress Frontend
│   └── Backend/PROGRESS.md                        # Progress Backend
├── app/Models/                                    # Model Eloquent (User, Mahasiswa, Dosen, Mitra, Program, Pendaftaran, ...)
├── database/migrations/                           # Skema basis data (users, mahasiswas, programs, pendaftarans, ...)
├── database/seeders/                              # Seeder data sampel tiap peran
├── routes/                                        # Rute web/api
└── README.md                                      # File ini
```

## Alur Kerja Tim

1. Buat **branch fitur** dari `develop` (`feature/nama-fitur`).
2. Kerjakan sampai **tanpa error**, catat progress di `Dokumen/<Frontend|Backend>/PROGRESS.md`.
3. Buka **Pull Request** ke `develop`, minta review.
4. Rilis ke `main` hanya oleh **Project Manager** via PR.

Detail lengkap: **[RULES.md](./RULES.md)**.

## Sprint

| Sprint | Minggu | Fokus |
|---|---|---|
| Sprint 1 | 6–7 | Autentikasi, hak akses, data master, peluang program, pendaftaran, pengajuan penempatan |
| Sprint 2 | 8–9 | Persetujuan dosen, seleksi mitra, kegiatan, dokumen, notifikasi, rekap |

## Tim

| Peran | Nama |
|---|---|
| Project Leader / PM | Muhammad Fajar Nurjaman |
| System Analyst | Ikhsan |
| Backend Developer | Ilham Almunawar |
| Frontend Developer | Riphan Romadlon, Fito Zulhian Jabatami |
| QA & Documentation | Fazna Laisal Ramadhan |
