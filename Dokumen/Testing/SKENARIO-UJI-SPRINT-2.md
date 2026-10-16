# Skenario Uji Sprint 2 — Sistem Kolaborasi Pendidikan & Industri

**Milestone:** M5 Sprint 2 (Minggu 8-9)  
**PIC QA:** Fazna Laisal Ramadhan, Riphan Romadlon  
**Tanggal:** 15 Oktober 2026  
**Lingkup:** Fitur Sprint 2 + Regresi Sprint 1

---

## 1. Setup Testing Environment

### 1.1 Prerequisites
```bash
# Clone repo (jika belum)
git clone https://github.com/Fajar1180/Sistem-Kolaborasi-Pendidikan-Industri.git
cd Sistem-Kolaborasi-Pendidikan-Industri

# Install dependencies
composer install
npm install

# Setup database
cp .env.example .env
# Edit .env: DB_DATABASE=skp_db, DB_USERNAME=root, DB_PASSWORD=

# Generate key & migrate
php artisan key:generate
php artisan migrate:fresh --seed

# Run server
php artisan serve
# Akses: http://localhost:8000
```

### 1.2 Test Accounts (dari seeder)
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@skp.test | password |
| Dosen 1 | dosen1@skp.test | password |
| Dosen 2 | dosen2@skp.test | password |
| Mahasiswa 1 | mhs1@skp.test | password |
| Mahasiswa 2 | mhs2@skp.test | password |
| Mitra 1 | mitra1@skp.test | password |
| Mitra 2 | mitra2@skp.test | password |

### 1.3 Browser Testing Matrix
- **Primary:** Chrome latest (desktop + mobile viewport)
- **Secondary:** Firefox atau Edge (desktop)
- **Devices:** Desktop 1920x1080, Mobile 375x667 (iPhone SE)

---

## 2. Fitur Sprint 2 yang Diuji

### S2-01: Persetujuan Dosen terhadap Pengajuan
**FR-04**: Dosen melihat, menyetujui, atau meminta revisi pengajuan mahasiswa bimbingannya.

**Skenario:**
1. Login sebagai `mhs1@skp.test`
2. Buat pendaftaran program (jika belum ada) → status `draf`
3. Edit dokumen → Ajukan → status jadi `diajukan`
4. Logout, login sebagai `dosen1@skp.test`
5. Buka menu **Pengajuan Masuk**
6. Lihat pendaftaran mahasiswa → detail
7. **Test Case A:** Klik **Setujui** → status jadi `disetujui_dosen`
8. **Test Case B:** Klik **Minta Revisi** + isi catatan → status jadi `revisi_dosen`, mahasiswa dapat notifikasi
9. Logout, login kembali sebagai `mhs1@skp.test`
10. Cek notifikasi → ada pesan revisi/approval
11. Jika revisi → edit dokumen → ajukan ulang

**Expected Result:**
- ✅ Dosen hanya lihat pengajuan mahasiswa bimbingannya (bukan semua)
- ✅ Button Setujui/Revisi muncul hanya jika status `diajukan`
- ✅ Setelah approve → mitra bisa lihat di seleksi
- ✅ Notifikasi in-app + email terkirim ke mahasiswa

**Regression Check:**
- ✅ Dosen tidak bisa approve pengajuan mahasiswa lain
- ✅ Status workflow tetap: draf → diajukan → disetujui_dosen

---

### S2-02: Seleksi Mitra + Kuota Program
**FR-05**: Mitra melihat pendaftaran yang sudah disetujui dosen, terima/tolak sesuai kuota.

**Skenario:**
1. Login sebagai `mitra1@skp.test`
2. Buat program baru dengan kuota 2 peserta (jika belum ada)
3. Publish program → status `aktif`
4. Logout, login sebagai `mhs1@skp.test` dan `mhs2@skp.test`
5. Daftar ke program mitra1 → ajukan → dosen approve (pakai dosen1)
6. Login kembali sebagai `mitra1@skp.test`
7. Buka **Seleksi Peserta** → lihat 2 pendaftaran status `disetujui_dosen`
8. **Test Case A:** Terima mahasiswa 1 → status `diterima_mitra`
9. **Test Case B:** Terima mahasiswa 2 → status `diterima_mitra`
10. **Test Case C:** Coba terima mahasiswa 3 (jika ada) → ditolak karena kuota penuh
11. **Test Case D:** Tolak mahasiswa → status `ditolak_mitra` + alasan

**Expected Result:**
- ✅ Mitra hanya lihat pendaftaran untuk program miliknya
- ✅ Kuota program berkurang otomatis saat terima peserta
- ✅ Jika kuota penuh → button Terima disabled atau validasi error
- ✅ Notifikasi ke mahasiswa (diterima/ditolak)
- ✅ Program otomatis tutup jika kuota terpenuhi (status `penuh` atau `ditutup`)

**Regression Check:**
- ✅ Mitra tidak bisa seleksi program mitra lain
- ✅ Kuota tidak bisa negatif

---

### S2-03: Logbook Kegiatan Mahasiswa
**FR-06**: Mahasiswa mencatat kegiatan harian/mingguan dengan status, foto, lampiran.

**Skenario:**
1. Login sebagai `mhs1@skp.test`
2. Pastikan ada pendaftaran status `berjalan` (jika belum, ubah manual via tinker/seeder)
3. Buka menu **Kegiatan** atau **Logbook**
4. **Test Case A:** Tambah kegiatan baru:
   - Tanggal: hari ini
   - Judul: "Rapat Kickoff"
   - Deskripsi: minimal 20 karakter
   - Status: Selesai/Dalam Progress
   - Upload foto (opsional, max 2MB)
5. Simpan → kegiatan muncul di list
6. **Test Case B:** Edit kegiatan → ubah deskripsi → simpan
7. **Test Case C:** Hapus kegiatan → confirm → hilang dari list
8. **Test Case D:** Coba tambah kegiatan tanpa tanggal/judul → validasi error

**Expected Result:**
- ✅ Mahasiswa hanya bisa tambah kegiatan untuk pendaftaran status `berjalan`
- ✅ Dosen pembimbing bisa lihat logbook mahasiswa (read-only)
- ✅ Upload foto max 2MB, format jpg/png
- ✅ Validasi: tanggal wajib, judul min 5 karakter, deskripsi min 20 karakter

**Regression Check:**
- ✅ Mahasiswa tidak bisa edit kegiatan mahasiswa lain
- ✅ Foto tersimpan di storage/app/public/kegiatan

---

### S2-04: Dokumen + Laporan Akhir + Flag TERLAMBAT
**FR-07**: Mahasiswa upload dokumen (proposal, laporan), sistem tandai jika terlambat dari deadline.

**Skenario:**
1. Login sebagai `mhs1@skp.test`
2. Buka pendaftaran status `berjalan` → tab **Dokumen**
3. **Test Case A:** Upload proposal:
   - File: PDF max 5MB
   - Deadline: 7 hari dari sekarang (lihat di UI)
   - Upload hari ini → status `tepat_waktu`
4. **Test Case B:** Upload laporan akhir:
   - File: DOCX/PDF max 10MB
   - Deadline: kemarin (ubah manual di DB/seeder untuk testing)
   - Upload hari ini → status `terlambat`, badge merah muncul
5. **Test Case C:** Coba upload file >10MB → validasi error
6. **Test Case D:** Coba upload .exe atau .zip → validasi error (hanya pdf/doc/docx)

**Expected Result:**
- ✅ Status TERLAMBAT muncul jika upload_at > deadline
- ✅ Badge merah "TERLAMBAT" di UI (proposal/laporan)
- ✅ Dosen + mitra bisa download dokumen mahasiswa
- ✅ Validasi: file size, extension, required fields

**Regression Check:**
- ✅ File tersimpan di storage/app/public/dokumen
- ✅ Mahasiswa tidak bisa hapus dokumen setelah di-review dosen

---

### S2-05: Bimbingan + Evaluasi Dosen
**FR-08**: Dosen memberikan catatan/feedback berkala ke mahasiswa, mahasiswa lihat history bimbingan.

**Skenario:**
1. Login sebagai `dosen1@skp.test`
2. Buka menu **Bimbingan Mahasiswa**
3. Pilih mahasiswa → lihat detail pendaftaran + logbook
4. **Test Case A:** Tambah catatan bimbingan:
   - Tanggal: hari ini
   - Catatan: "Progres bagus, lanjutkan..."
   - Rating: 4/5 (opsional)
5. Simpan → mahasiswa dapat notifikasi
6. Logout, login sebagai `mhs1@skp.test`
7. Buka **Bimbingan Saya** atau pendaftaran detail → tab **Bimbingan**
8. Lihat catatan dari dosen → bisa reply (opsional)

**Expected Result:**
- ✅ Dosen hanya bisa beri feedback ke mahasiswa bimbingannya
- ✅ History bimbingan tersimpan dengan timestamp
- ✅ Mahasiswa dapat notifikasi in-app + email (jika configured)
- ✅ Rating 1-5 (opsional), catatan min 10 karakter

**Regression Check:**
- ✅ Dosen tidak bisa edit bimbingan dosen lain
- ✅ Mahasiswa tidak bisa hapus catatan bimbingan

---

### S2-06: Penilaian Mitra terhadap Mahasiswa
**FR-09**: Mitra memberikan nilai akhir (1-100) + feedback untuk mahasiswa yang selesai.

**Skenario:**
1. Login sebagai `mitra1@skp.test`
2. Buka menu **Penilaian Peserta**
3. Lihat mahasiswa dengan status `berjalan` atau `menunggu_penilaian`
4. **Test Case A:** Beri nilai mahasiswa 1:
   - Nilai: 85 (range 0-100)
   - Feedback: "Kerja bagus, tepat waktu"
   - Rekomendasi: Ya/Tidak
5. Simpan → status pendaftaran jadi `selesai`
6. **Test Case B:** Coba beri nilai 150 → validasi error (max 100)
7. **Test Case C:** Coba beri nilai tanpa feedback → validasi error (min 20 karakter)

**Expected Result:**
- ✅ Mitra hanya bisa nilai mahasiswa di program miliknya
- ✅ Nilai range 0-100, feedback min 20 karakter
- ✅ Setelah dinilai → status `selesai`, mahasiswa dapat notifikasi
- ✅ Nilai muncul di dashboard mahasiswa + admin rekap

**Regression Check:**
- ✅ Mitra tidak bisa edit nilai setelah submit (atau ada button Edit)
- ✅ Mahasiswa tidak bisa edit nilai yang diberikan mitra

---

### S2-07: Notifikasi In-App + Email
**FR-10**: Notifikasi muncul untuk event penting (approval, rejection, bimbingan, penilaian).

**Skenario:**
1. Login sebagai `mhs1@skp.test`
2. Buat/ajukan pendaftaran → dosen approve
3. **Test Case A:** Cek notifikasi in-app (icon lonceng 🔔):
   - Badge count muncul (angka merah)
   - Dropdown menampilkan 5 notifikasi terbaru
   - Klik notifikasi → redirect ke halaman terkait (pendaftaran detail)
4. **Test Case B:** Cek email (jika SMTP configured):
   - Email masuk ke mhs1@skp.test
   - Subject: "Pengajuan Anda Disetujui Dosen"
   - Body: ringkasan + link ke dashboard
5. **Test Case C:** Arsipkan notifikasi:
   - Buka halaman **Notifikasi** → list semua notifikasi
   - Klik "Tandai Dibaca" → badge count berkurang
   - Klik "Arsipkan" → notifikasi hilang dari list

**Expected Result:**
- ✅ Notifikasi muncul real-time (refresh halaman jika tanpa WebSocket)
- ✅ Badge count = total notifikasi belum dibaca
- ✅ Email terkirim jika SMTP configured (optional, bisa skip jika dev)
- ✅ Link notifikasi mengarah ke halaman yang benar

**Regression Check:**
- ✅ User hanya lihat notifikasi miliknya
- ✅ Notifikasi lama (>30 hari) bisa dihapus otomatis (jika ada cron)

---

### S2-08: Rekap & Ekspor Laporan Admin
**FR-15/FR-16**: Admin melihat rekap per program/prodi/mitra, ekspor ke Excel/CSV.

**Skenario:**
1. Login sebagai `admin@skp.test`
2. Buka menu **Rekap & Laporan**
3. **Test Case A:** Rekap semua pendaftaran:
   - Filter: Status = Berjalan
   - Filter: Prodi = Teknik Informatika
   - Lihat tabel + statistik (total, berjalan, selesai)
4. **Test Case B:** Rekap per program:
   - Klik tab **Per Program**
   - Lihat total pendaftaran per program + nama mitra
5. **Test Case C:** Rekap per prodi:
   - Klik tab **Per Prodi**
   - Lihat total mahasiswa per program studi
6. **Test Case D:** Rekap per mitra:
   - Klik tab **Per Mitra**
   - Lihat total program + pendaftaran per mitra
7. **Test Case E:** Ekspor Excel:
   - Klik button **Ekspor Excel**
   - File .csv terdownload (pendaftaran.csv)
   - Buka file → data lengkap dengan header

**Expected Result:**
- ✅ Filter bekerja (status, prodi, periode)
- ✅ Pagination 20 items per page
- ✅ Statistik card di atas tabel update sesuai filter
- ✅ File CSV berisi: NIM, Nama Mahasiswa, Program, Mitra, Status, Tanggal
- ✅ Format tanggal Indonesia: dd/mm/yyyy

**Regression Check:**
- ✅ Admin bisa lihat semua data (tidak filtered by user)
- ✅ Ekspor tidak timeout untuk 1000+ records

---

## 3. Regresi Sprint 1

### 3.1 Autentikasi & Hak Akses
- ✅ Login 4 role (admin, dosen, mitra, mahasiswa) berhasil
- ✅ Logout redirect ke login
- ✅ Forgot password kirim email reset (jika SMTP on)
- ✅ Access control: mahasiswa tidak bisa akses /admin/*
- ✅ Access control: dosen tidak bisa akses /mitra/*

### 3.2 Master Data (Admin)
- ✅ CRUD Pengguna: tambah, edit, nonaktifkan, hapus
- ✅ CRUD Program Studi
- ✅ CRUD Periode Akademik
- ✅ Import CSV: mahasiswa, dosen, mitra (sample file valid)

### 3.3 Peluang Program (Mahasiswa & Mitra)
- ✅ Mahasiswa lihat list program aktif
- ✅ Mahasiswa search/filter program (by mitra, kategori)
- ✅ Mahasiswa detail program → klik Daftar
- ✅ Mitra buat program baru → publish
- ✅ Mitra edit/tutup program

### 3.4 Pendaftaran & Pengajuan
- ✅ Mahasiswa daftar program → status draf
- ✅ Mahasiswa lengkapi data → ajukan → status diajukan
- ✅ Mahasiswa lihat list pendaftaran + status badge

---

## 4. Test Cases Summary

| ID | Fitur | Test Cases | Priority |
|----|-------|------------|----------|
| T1 | Persetujuan Dosen | Approve, Revisi, Notifikasi | P0 |
| T2 | Seleksi Mitra | Terima, Tolak, Kuota Full | P0 |
| T3 | Logbook Mahasiswa | CRUD kegiatan, Upload foto | P1 |
| T4 | Dokumen + TERLAMBAT | Upload PDF, Flag terlambat | P0 |
| T5 | Bimbingan Dosen | Feedback, Rating, History | P1 |
| T6 | Penilaian Mitra | Nilai 0-100, Feedback | P0 |
| T7 | Notifikasi | In-app badge, Email, Arsip | P0 |
| T8 | Rekap Admin | Filter, Ekspor Excel | P1 |
| R1 | Regresi Sprint 1 | Login, CRUD, Pendaftaran | P0 |

**Priority:**
- P0 = Must pass (blocker)
- P1 = Should pass (high)
- P2 = Nice to have (low)

---

## 5. Bug Reporting Template

Jika menemukan bug, buat GitHub issue dengan format:

```markdown
## Bug: [Judul singkat]

**Severity:** Critical / High / Medium / Low
**Sprint:** Sprint 2
**Feature:** [S2-01 Persetujuan Dosen / S2-02 Seleksi Mitra / ...]

### Steps to Reproduce
1. Login sebagai mhs1@skp.test
2. Buka menu Pendaftaran
3. Klik Ajukan
4. ...

### Expected Result
Status berubah jadi "diajukan" dan notifikasi terkirim.

### Actual Result
Status masih "draf", no error message.

### Environment
- OS: Windows 11
- Browser: Chrome 119
- Device: Desktop 1920x1080
- Laravel: 11.x
- PHP: 8.2

### Screenshots
[Lampirkan screenshot jika ada]

### Possible Cause
[Opsional: analisis awal]
```

**Labels:** `bug`, `sprint-2`, `high` / `medium` / `low`

---

## 6. Draf Skenario UAT (Minggu 11)

### 6.1 User Acceptance Criteria
- **Pengguna Real:** Mahasiswa, dosen, mitra dari kampus (bukan test account)
- **Durasi:** 2 hari (setup + test + feedback)
- **Environment:** Staging server (bukan localhost)

### 6.2 UAT Test Scenarios (Simplified)
1. **End-to-End Happy Path:**
   - Mahasiswa cari program → daftar → ajukan
   - Dosen approve
   - Mitra terima
   - Mahasiswa isi logbook + upload dokumen
   - Dosen beri feedback
   - Mitra beri nilai
   - Status jadi selesai

2. **Rejection Path:**
   - Dosen minta revisi → mahasiswa perbaiki → ajukan ulang
   - Mitra tolak → mahasiswa daftar program lain

3. **Admin Monitoring:**
   - Admin lihat rekap real-time
   - Admin ekspor data untuk laporan bulanan

### 6.3 UAT Success Metrics
- ✅ 90% test cases pass
- ✅ No critical bugs (P0 blocker)
- ✅ User feedback positif (>3/5 satisfaction)
- ✅ Performance: halaman load <3s

---

## 7. Dokumentasi Akhir Sprint

### 7.1 File yang Harus Diupdate
- `Dokumen/Frontend/PROGRESS.md` → catat fitur UI Sprint 2 yang selesai
- `Dokumen/Backend/PROGRESS.md` → catat controller/model/migration Sprint 2
- `Dokumen/Testing/HASIL-UJI-SPRINT-2.md` → hasil testing + bug found

### 7.2 Sprint Review Deliverables
- ✅ Demo aplikasi (happy path end-to-end)
- ✅ Slide presentasi (what's done, what's next, metrics)
- ✅ Bug list + fix timeline
- ✅ Backlog refinement untuk Sprint 3 (jika ada)

---

## 8. Checklist Akhir

- [ ] Semua 8 fitur Sprint 2 ditest (T1-T8)
- [ ] Regresi Sprint 1 pass (R1)
- [ ] Bug ditemukan dicatat sebagai GitHub issues
- [ ] Screenshot bukti testing disimpan
- [ ] Draf UAT scenarios dibuat
- [ ] Progress.md diupdate
- [ ] Sprint review slides prepared
- [ ] PR `docs/uji-sprint-2` dibuat ke main

---

**PIC QA:** Fazna Laisal Ramadhan, Riphan Romadlon  
**Review by:** Ikhsan (SA), Fajar (PM)  
**Deadline:** Minggu 9 (sebelum sprint review)
