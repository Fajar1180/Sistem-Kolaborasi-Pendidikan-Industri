# GUIDE: Setup & Testing Sistem Kolaborasi Pendidikan-Industri di Laragon

**Dibuat:** 16 Oktober 2026  
**Untuk:** Testing Sprint 2 + Demo  
**Environment:** Laragon (Windows 11) + MySQL 8.0 + PHP 8.2+

---

## 📋 Prerequisites

### Step 1: Install Laragon (Jika Belum)
1. Download: https://laragon.org/download/
2. Install ke `C:\laragon\` (default)
3. Jalankan Laragon → **Start All** (Apache + MySQL on)

### Step 2: Verify Prerequisites
Buka **Terminal/CMD** dan run:

```bash
php -v
# Expected: PHP 8.2+ (jika <8.2, update Laragon)

composer -V
# Expected: Composer 2.x+

mysql -V
# Expected: MySQL 8.0+

node -v
npm -v
# Expected: Node 18+
```

---

## 🔧 Setup Aplikasi

### Step 1: Clone/Pull Repository

```bash
# Jika belum clone:
git clone https://github.com/Fajar1180/Sistem-Kolaborasi-Pendidikan-Industri.git
cd Sistem-Kolaborasi-Pendidikan-Industri

# Jika sudah clone, pull latest:
git checkout main
git pull origin main
```

### Step 2: Install Dependencies

```bash
# Backend
composer install

# Frontend
npm install
npm run build
```

### Step 3: Setup Environment File

```bash
# Copy .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Edit .env dengan MySQL credentials:
# DB_DATABASE=skp_db
# DB_USERNAME=root
# DB_PASSWORD= (kosong)
```

**Atau** gunakan Laragon Notepad:
- Klik Laragon → Menu (3 garis) → Open Terminal Here
- Edit file `.env` dengan setting MySQL sesuai Laragon default

### Step 4: Setup Database

```bash
# Buat database baru
mysql -u root -e "CREATE DATABASE IF NOT EXISTS skp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrasi + seeder (init data dummy)
php artisan migrate:fresh --seed

# Verifikasi
php artisan tinker
# Ketik: User::count() → harusnya return 7 (admin + 2 dosen + 5 mhs/mitra)
# exit
```

### Step 5: Run Laravel Server

```bash
php artisan serve
# Server starts at: http://localhost:8000
```

**Di terminal lain**, compile CSS real-time (optional):
```bash
npm run dev
```

---

## 👤 Test Accounts (dari Seeder)

Login dengan password: `password`

| Role | Email | Fungsi |
|------|-------|--------|
| **Admin** | admin@skp.test | Kelola user, program, rekap |
| **Dosen 1** | dosen1@skp.test | Review pengajuan, bimbingan |
| **Dosen 2** | dosen2@skp.test | Tambahan backup dosen |
| **Mahasiswa 1-5** | mhs1@skp.test … mhs5@skp.test | Daftar program, ajukan |
| **Mitra 1-2** | mitra1@skp.test, mitra2@skp.test | Buat program, seleksi |

---

## 🧪 Testing Checklist

### Phase 1: Smoke Test (5 menit)
Pastikan aplikasi bisa dibuka tanpa error.

```
✅ Akses http://localhost:8000 → landing page muncul
✅ Landing page: judul, 4 role cards, CTA login
✅ Klik "Masuk ke Akun" → login page
✅ Login dengan admin@skp.test / password → dashboard muncul
✅ Dashboard: welcome card + stat cards (admin-specific)
✅ Navbar: lonceng notifikasi + menu hamburger (mobile)
✅ Logout → redirect ke login
```

### Phase 2: Core Features (30 menit)

#### A. Authentikasi & Dashboard
```
□ Login 4 role berbeda (admin, dosen, mahasiswa, mitra)
□ Setiap role → dashboard berbeda (stat cards custom)
□ Dashboard admin: total users, program, berjalan, menunggu
□ Dashboard dosen: pengajuan menunggu, mahasiswa bimbingan
□ Dashboard mitra: program aktif, pendaftaran masuk
□ Dashboard mahasiswa: total pendaftaran, status terbaru
□ Logout → session clear, redirect ke login
□ Session timeout (15 menit) → redirect login
□ 403 Forbidden: mahasiswa akses /admin/* → error page
```

#### B. Program Catalog (Mahasiswa)
```
□ Login mhs1@skp.test → Akses http://localhost:8000/mahasiswa/program
□ List program dengan filter (mitra, kategori)
□ Detail program → info mitra, kuota, deadline
□ Klik "Daftar" → form konfirmasi
□ Ajukan pendaftaran → status jadi "draf"
□ Edit pendaftaran → lengkapi data
□ Ajukan → status jadi "diajukan", notifikasi dosen
□ Batal → status back to "draf"
```

#### C. Persetujuan Dosen (S2-01)
```
□ Login dosen1@skp.test → Menu "Pengajuan Masuk"
□ Lihat pengajuan mahasiswa bimbingannya
□ Detail → lihat dokumen, catatan mahasiswa
□ Klik "Setujui" → status "disetujui_dosen", notifikasi ke mahasiswa
□ Atau klik "Minta Revisi" + isi catatan → status "revisi_dosen"
□ Mahasiswa dapat notifikasi + lihat catatan revisi
□ Notifikasi badge di navbar berkurang (jika dibaca)
```

#### D. Seleksi Mitra (S2-02)
```
□ Login mitra1@skp.test → Menu "Peluang Saya"
□ Lihat program dengan pendaftaran "disetujui_dosen"
□ Menu "Seleksi Peserta" → list mahasiswa per program
□ Klik "Terima" → status "diterima_mitra", kuota berkurang
□ Jika kuota penuh → "Terima" disabled atau error
□ Atau klik "Tolak" + alasan → status "ditolak_mitra"
□ Mahasiswa dapat notifikasi
```

#### E. Notifikasi (S2-07)
```
□ Trigger notifikasi: approve dosen, tolak mitra, bimbingan, penilaian
□ Badge lonceng 🔔 di navbar muncul dengan count
□ Klik lonceng → dropdown 5 notifikasi terbaru
□ Klik notifikasi → redirect ke halaman terkait
□ Buka http://localhost:8000/notifikasi → list semua
□ Tandai dibaca → badge count berkurang
□ Arsipkan → hilang dari list
```

#### F. Admin Rekap (S2-08)
```
□ Login admin@skp.test → Menu "Rekap & Laporan"
□ Lihat statistik: total, berjalan, selesai, menunggu
□ Filter: status, prodi, periode → statistik update
□ Tab "Per Program" → total per program
□ Tab "Per Prodi" → total per program studi
□ Tab "Per Mitra" → total per perusahaan
□ Ekspor Excel → download CSV
□ Buka CSV di Excel → data lengkap, format Indonesia
```

### Phase 3: Edge Cases & Errors (15 menit)

```
□ Login dengan email salah → error message muncul
□ Login dengan password salah → error message muncul
□ Lupa password → kirim reset link (jika SMTP on, skip jika dev)
□ Register route /register → redirect ke login dengan info banner
□ Akses /admin/* sebagai mahasiswa → 403 error page
□ Akses /mitra/* sebagai dosen → 403 error page
□ Coba upload file >5MB → validasi error
□ Form submit tanpa data wajib → validasi error muncul
□ Kuota program = 0 → tidak bisa terima mahasiswa baru
□ Delete button → confirm modal / confirm browser alert
```

### Phase 4: Browser & Mobile (10 menit)

```
□ Desktop (1920x1080): semua halaman responsive, no horizontal scroll
□ Mobile (375x667): hamburger menu works, layout stack vertikal
□ Chrome + Firefox: styling konsisten, no console errors
□ Print page: layout printable (rekap bisa di-print)
□ Zoom in/out: text readable, button clickable
```

---

## 🐛 Bug Reporting

Jika menemukan bug/error, buat GitHub issue dengan template:

```markdown
## Bug: [Judul]

**Severity:** Critical / High / Medium / Low

### Steps to Reproduce
1. Login sebagai admin@skp.test
2. Go to /admin/rekap
3. Click "Ekspor Excel"
4. ...

### Expected
File .csv terdownload dengan data lengkap

### Actual
Error 500 atau file kosong

### Screenshot
[Paste screenshot]

### Environment
- OS: Windows 11
- Browser: Chrome 119
- Laravel: 11.x
- PHP: 8.2
```

**Labels:** `bug`, `sprint-2`, `high/medium/low`

---

## 📊 Testing Report

Setelah testing, isi tabel ini dan update GitHub:

```markdown
## Testing Result Sprint 2

**Date:** [Date]
**Tester:** [Your name]
**Environment:** Laragon + Chrome 119

### Summary
- Total Test Cases: 50+
- Passed: XX
- Failed: XX
- Pass Rate: XX%

### Features Status
- [ ] S2-01 Persetujuan Dosen: ✅/⚠️/❌
- [ ] S2-02 Seleksi Mitra: ✅/⚠️/❌
- [ ] S2-03 Logbook: ✅/⚠️/❌
- [ ] S2-04 Dokumen: ✅/⚠️/❌
- [ ] S2-05 Bimbingan: ✅/⚠️/❌
- [ ] S2-06 Penilaian: ✅/⚠️/❌
- [ ] S2-07 Notifikasi: ✅/⚠️/❌
- [ ] S2-08 Rekap: ✅/⚠️/❌

### Bugs Found
- [#XX] Bug title - severity P0/P1/P2
- [#YY] Another bug - severity

### Recommendation
[Overall pass/fail, blockers, next steps]
```

---

## 🆘 Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1030"
→ MySQL out of memory. Fix: `php artisan migrate:fresh --seed` (reset db)

### Error: "No application encryption key has been defined"
→ Run: `php artisan key:generate`

### Error: 500 Internal Server Error
→ Check `storage/logs/laravel.log`:
```bash
tail -100 storage/logs/laravel.log
```

### Notifikasi tidak muncul
→ Refresh halaman (no WebSocket di dev)
→ Check: `SELECT * FROM notifikasis;` di MySQL

### File upload tidak jalan
→ Chmod `storage/app/public/` recursive 777
→ Run: `php artisan storage:link`

### CSS/JS tidak load
→ Run: `npm run build` (rebuild assets)
→ Clear browser cache: Ctrl+Shift+Delete

### Database sudah ada data, mau reset
→ Run: `php artisan migrate:fresh --seed` (warning: hapus semua data!)

---

## 📝 Useful Commands

```bash
# Developer commands
php artisan tinker           # PHP REPL untuk testing
php artisan migrate:fresh    # Reset DB (hapus semua data!)
php artisan migrate:rollback # Undo migration terakhir
php artisan db:seed          # Run seeder ulang

# Queue jobs (if using async)
php artisan queue:work       # Process background jobs
php artisan queue:listen     # Continuous listen

# Cache
php artisan cache:clear      # Clear semua cache
php artisan config:cache     # Cache konfigurasi

# Logs
tail -f storage/logs/laravel.log  # Real-time log
php artisan log:tail              # Laravel log viewer

# Database
mysql -u root                # Connect MySQL
SHOW DATABASES;              # List DB
USE skp_db;                  # Select DB
SHOW TABLES;                 # List tables
SELECT * FROM users;         # View users
EXIT;                        # Quit MySQL
```

---

## 🎯 Success Criteria

✅ Testing PASS jika:
1. Semua 8 fitur Sprint 2 berfungsi
2. Regresi Sprint 1 tetap hijau
3. No critical bugs (P0 blocker)
4. Responsif desktop + mobile
5. No console errors (F12 → Console)

---

## 📞 Next Steps

1. **Setup Laragon** → follow Step 1-2 di atas
2. **Setup aplikasi** → Step 1-5
3. **Test** → ikuti Phase 1-4
4. **Report** → buat GitHub issue untuk setiap bug
5. **Update progress** → edit Dokumen/Testing/HASIL-UJI-SPRINT-2.md

---

**PIC Testing:** Fazna Laisal Ramadhan, Riphan Romadlon  
**Support:** Ikhsan (SA), Fajar (PM)  
**Deadline:** Minggu 9 (sebelum sprint review)  
**Questions?** Create issue dengan label `question` atau contact team

🚀 **Happy Testing!**
