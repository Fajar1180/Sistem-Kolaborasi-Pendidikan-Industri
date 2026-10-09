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

# 2. (Ketika struktur Laravel sudah ada) Install dependensi
composer install
npm install

# 3. Siapkan environment lokal
cp .env.example .env
php artisan key:generate
# lalu isi kredensial database di .env (MySQL Laragon)

# 4. Migrasi + seeder data dummy (mahasiswa, dosen, admin, mitra)
php artisan migrate --seed

# 5. Jalankan
php artisan serve
```

> **Penting:** setiap programmer WAJIB menjalankan `php artisan migrate --seed` setelah `git pull` agar mendapat data sampel terbaru untuk testing — tidak perlu input manual.

## Struktur Dokumen

```
├── Dokumen Perencanaan Proyek … (Kelompok 6).md   # KAK, Charter, WBS, Jadwal, Sprint Backlog, PRD
├── RULES.md                                       # Aturan kerja tim (branch, PR, dokumentasi, AI)
├── Dokumen/
│   ├── Frontend/PROGRESS.md                       # Progress Frontend
│   └── Backend/PROGRESS.md                        # Progress Backend
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
