# Uji Fungsi Sprint 2 dan Dokumentasi
**Sprint:** 2 (Minggu 8-9)  
**Issue:** [S2-10] Uji fungsi Sprint 2 dan dokumentasi  
**Assignee:** 1khsan2610 (System Analis)  

## 1. Tujuan
Uji semua increment Sprint 2 dan buat dokumentasi lengkap untuk sprint review minggu 9 serta persiapan UAT minggu 11.

## 2. Skenario Uji Sprint 2
### 2.1 Persetujuan Dosen
- **Langkah:** Dosen masuk ke halaman pending-placements, pilih penempatan yang menunggu persetujuan, klik "Approve".
- **Ekspektasi:** Status penempatan berubah menjadi "approved_by_dosen", notifikasi terkirim ke mitra & mahasiswa, jejak audit tercatat.
- **Test Cases:**
  1. Dosen approve penempatan valid → status berubah, notifikasi terkirim.
  2. Dosen approve penempatan yang sudah ditolak → gagal, error "Penempatan tidak dapat diapprove".
  3. Dosen approve penempatan yang sudah approved → gagal, error "Sudah disetujui".

### 2.2 Seleksi & Kuota Mitra
- **Langkah:** Mitra masuk ke halaman placement-applications, pilih peserta, klik "Accept" atau "Reject".
- **Ekspektasi:** Status peserta berubah menjadi "selected_by_mitra" atau "rejected_by_mitra", kuota mitra berkurang (jika accept), notifikasi terkirim.
- **Test Cases:**
  1. Mitra accept peserta → status berubah, kuota berkurang, notifikasi terkirim.
  2. Mitra reject peserta → status berubah, alasan tercatat, kuota tidak berubah.
  3. Mitra accept > kuota → gagal, error "Kuota tidak mencukupi".

### 2.3 Logbook Kegiatan
- **Langkah:** Mahasiswa mengakses halaman logbook, melihat daftar kegiatan (berikut milestones: pendaftaran, penempatan, kegiatan, laporan).
- **Ekspektasi:** Logbook menampilkan semua kegiatan sesuai status, filter per peran, pencarian, dan pencarian tanggal.
- **Test Cases:**
  1. Mahasiswa melihat logbook → semua kegiatan terlihat sesuai status.
  3. Filter logbook per status (draft, approved, completed) → hanya kegiatan sesuai filter yang tampil.
  4. Cari kegiatan berdasarkan tanggal → hasil pencarian tepat.

### 2.4 Dokumen & Laporan (Terlambat)
- **Langkah:** Mahasiswa mengunggah dokumen (PDF, JPG, PNG, DOCX) lewat form laporan, kemudian menandai "terlambat".
- **Ekspektasi:** Sistem menandai status "terlambat", mengirim notifikasi email, dan menambahkan logbook "terlambat" pada jejak audit.
- **Test Cases:**
  1. Upload dokumen terlambat → status terlambat, notifikasi terkirim.
  2. Upload dokumen valid (bukan terlambat) → status "draft" atau "pending", tidak terlambat.
  3. Upload file dengan ukuran >5MB → error validasi.

### 2.5 Bimbingan & Evaluasi
- **Langkah:** Dosen membuka halaman bimbingan, memilih mahasiswa, mengisi form evaluasi (skor 1-5, komentar).
- **Ekspektasi:** Nilai evaluasi disimpan, dapat dilihat oleh mahasiswa & mitra, jejak audit tercatat.
- **Test Cases:**
  1. Dosen mengisi evaluasi → nilai tersimpan, dapat dilihat oleh semua pihak.
  2. Dosen mengubah evaluasi → perubahan tercatat, validasi nilai 1-5.

### 2.5 Penilaian Mitra
- **Langkah:** Mitra mengakses halaman penilaian, memilih mahasiswa, mengisi skor dan komentar.
- **Ekspektasi:** Skor dan komentar tersimpan, dapat dilihat oleh mahasiswa, dosen, dan admin.
- **Test Cases:**
  1. Mitra mengisi penilaian → nilai tersimpan, dapat dilihat oleh semua.
  2. Mitra mengubah penilaian → perubahan tercatat, jejak audit.

### 2.6 Notifikasi In-App & Email
- **Langkah:** Setiap aksi penting (approve, reject, upload, evaluasi) menghasilkan notifikasi in-app dan email ke pihak terlibat.
- **Ekspektasi:** Notifikasi muncul di UI, email terkirim dengan template yang sesuai, dan jejak audit mencatat aksi.
- **Test Cases:**
  1. Approve penempatan → notifikasi in-app & email terkirim.
  2. Upload dokumen terlambat → notifikasi in-app & email terkirim.
  3. Evaluasi ditolak → notifikasi error, email terkirim.

### 2.7 Rekapitulasi & Ekspor Laporan
- **Langkah:** Admin mengakses halaman rekapitulasi, memilih periode, menekan "Export CSV" atau "Export PDF".
- **Ekspektasi:** File ekspor berisi semua data (pengguna, program, pendaftaran, penempatan, evaluasi) sesuai format, dapat diunduh, dan tidak menghasilkan error.
- **Test Cases:**
  1. Export CSV → file terbuka di spreadsheet, data lengkap.
  2. Export PDF → file terbuka di PDF viewer, semua data terlihat.
  3. Ekspor pada periode kosong → file kosong atau error yang jelas.

## 3. Regressi Sprint 1
- Pastikan semua fitur Sprint 1 tetap berfungsi:
  - Login/logout, atur ulang kata sandi
  - Hak akses peran (role, active)
  - CRUD pengguna & data master (admin)
  - Pengelolaan program & peluang (mitra)
  - Pendaftaran & penempatan (mahasiswa)
  - Notifikasi & jejak audit

## 3. Dokumentasi UAT (Minggu 11)
- Draf skenario UAT (format sama dengan skenario uji) sebelum minggu 11:
  - Contoh skenario: Mahasiswa mendaftar program → mengajukan penempatan → mitra seleksi → dosen approve → evaluasi → laporan → rekapitulasi.
  - Template skenario: ID, peran, langkah, data input, output, hasil yang diharapkan.

## 4. Kriteria Penerimaan
- Semua skenario **Must** Sprint 2 lolos uji.
- Regressi Sprint 1 tetap hijau (semua 59 uji tes).
- Draf skenario UAT tersedia sebelum minggu 11.
- Dokumentasi akhir sprint (yang selesai) tersedia di `Dokumen/` dengan catatan hal yang tertunda (backlog/UAT fix list).

## 5. Estimasi & Branch
- Estimasi: **3 SP** (~9 jam)
- Branch: `feature/s2-10-test-plan` (dari `develop`)

## 6. Definition of Done
- Semua skenario uji lulus, regresi Sprint 1 hijau.
- PR dokumentasi (`docs/uji-sprint-2`) terbuka, review, dan merge ke `develop`.
- Dokumentasi lengkap di `Dokumen/` (sesi akhir minggu 9).
- Catatan hal yang tertunda (backlog) tercatat di `Dokumen/Backlog-UAT.md`.

## 7. Dokumentasi yang Sudah Diciptakan
- `Dokumen/Sprint1/SPRINT-1-SRS.md` — acuan implementasi Sprint 1
- `Dokumen/Sprint1/API-SPECIFICATION.md` — kontrak API untuk Sprint 1
- `Dokumen/Sprint1/UI-UX.md` — desain UI/UX Sprint 1

## 7. Dokumentasi yang Perlu Dibuat
- `Dokumen/Sprint2/UJI-SPRINT-2.md` — **dokumen ini** (test plan & skenario)
- `Dokumen/Backlog-UAT.md` — daftar backlog / item UAT yang belum selesai
- `Dokumen/Progress.md` — update progres log backend & frontend (sudah ada, cek kembali)

## 7.1. Catatan Tambahan
- Pastikan semua skenario diuji pada **Chrome** (desktop) dan **Chrome** (mobile emulator) + **Firefox** (desktop) sebagai minimum.
- Catat semua temuan sebagai issue `bug` dengan label `sprint-2` di GitHub Issues.
- Jika ada bug kritis, prioritisikannya harus lebih tinggi daripada skenario non-kritis.

## 7.2. Dokumentasi UAT (Minggu 11)
- Siapkan **draf skenario UAT** (format: ID, peran, langkah, data input, output, hasil diharapkan) sebelum minggu 11.
- Gunakan template berikut:
  ```
  ID: UAT-XX
  Peran: [Mahasiswa/Dosen/Mitra/Admin]
  Langkah: [1, 2, 3...]
  Data Input: [deskripsi]
  Output yang Diharapkan: [deskripsi]
  Hasil yang Dicapai: [deskripsi]
  Keterangan: [catatan]
  ```

## 8. Dokumentasi yang Perlu Disiapkan
- `Dokumen/Sprint2/UJI-SPRINT-2.md` — **dokumen ini**
- `Dokumen/Backlog-UAT.md` — daftar backlog / item UAT yang belum selesai (misal: bug kritis yang belum diperbaiki)
- Update `Dokumen/Frontend/PROGRESS.md` dan `Dokumen/Backend/PROGRESS.md` dengan progres uji Sprint 2.

## 8.1. Catatan Penting
- Pastikan semua skenario diuji pada **Chrome** (desktop) dan **Chrome** (mobile) + **Firefox** (desktop) sebagai minimum.
- Gunakan **test case management** (mis. TestRail, Zephyr, atau simple spreadsheet) jika diperlukan, tapi minimal catat di file markdown di `Dokumen/`.
- Jika menemukan bug, buat issue terpisah dengan label `bug` dan `sprint-2`, lalu hubungkan ke issue ini lewat `Closes #<nomor>`.

**Catatan Penting:**  
- **Tim QA (Fazna)** akan menjalankan skenario uji, sementara **SA (Anda)** menyusun dokumentasi, skenario UAT, dan memastikan semua kriteria penerimaan terpenuhi sebelum sprint review.
- **Tim Frontend** harus memastikan UI responsif dan komponen reusable (S1-05) tidak terbrek di uji Sprint 2.

---

*Dokumen ini akan menjadi acuan utama untuk QA Sprint 2 dan persiapan UAT minggu 11. Selamat bekerja!*