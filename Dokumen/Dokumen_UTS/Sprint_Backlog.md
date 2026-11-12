# Sprint Backlog – Sistem Informasi Kolaborasi Pendidikan dan Industri

**Kelompok:** 6  
**TA:** 2026  
**Tanggal disusun:** 9 Oktober 2026  
**Metode estimasi:** 1 Story Point (SP) ≈ 3 jam kerja  
**Kapasitas tiap sprint:** ±160 jam atau ±50 SP  

---

## 1. Pengantar

Sprint Backlog diturunkan dari kebutuhan fungsional pada PRD/SRS (Bagian 7 dokumen perencanaan). Pengembangan sistem dilakukan dalam dua sprint berdurasi 2 minggu masing-masing, diapit oleh tahap analisis dan perancangan (minggu 1–5) serta pengujian dan implementasi (minggu 10–12). Setiap item berstatus **Belum Mulai** pada saat dokumen ini disusun dan akan diperbarui ke *Done* setelah lulus uji fungsi QA dan direview pada sprint review.

Definition of Done (DoD): kode direview dan digabung ke cabang utama, memenuhi kriteria penerimaan PRD, lulus uji fungsi QA, tampil baik di layar ponsel dan desktop, serta tercatat dalam dokumentasi sprint.

---

## 2. Sprint 1 (Minggu 6–7)

**Sprint Goal:** Pengguna dapat masuk sesuai perannya, admin dapat mengelola data master, mitra dapat mempublikasikan peluang program, dan mahasiswa dapat mendaftar program serta mengajukan penempatan.

**Total:** 49 SP (±147 jam)

| ID | Item Pekerjaan | Ref PRD | PIC | SP | Status | Keterangan |
|----|----------------|---------|-----|----|--------|------------|
| S1-01 | Penyiapan repositori, lingkungan pengembangan, dan struktur proyek | NFR-07 | BE | 3 | Belum Mulai | |
| S1-02 | Skema basis data dan migrasi awal | Semua FR | BE | 5 | Belum Mulai | |
| S1-03 | Autentikasi: masuk, keluar, dan atur ulang kata sandi | FR-01 | BE | 5 | Belum Mulai | |
| S1-04 | Hak akses berdasarkan empat peran | FR-02 | BE | 5 | Belum Mulai | |
| S1-05 | Tata letak responsif dan komponen antarmuka dasar | NFR-03 | FE | 5 | Belum Mulai | |
| S1-06 | Admin: pengelolaan pengguna dan data master | FR-14 | BE, FE | 8 | Belum Mulai | |
| S1-07 | Mitra: profil dan publikasi peluang program | FR-11 | BE, FE | 5 | Belum Mulai | |
| S1-08 | Mahasiswa: melihat peluang dan mendaftar program | FR-03 | BE, FE | 5 | Belum Mulai | |
| S1-09 | Mahasiswa: pengajuan penempatan | FR-04 | BE, FE | 5 | Belum Mulai | |
| S1-10 | Uji fungsi Sprint 1 dan dokumentasi | Semua item sprint | QA | 3 | Belum Mulai | |

---

## 3. Sprint 2 (Minggu 8–9)

**Sprint Goal:** Seluruh alur kolaborasi empat peran berjalan utuh, mulai dari persetujuan dan seleksi, pencatatan kegiatan, pembimbingan, penilaian, hingga rekapitulasi dan notifikasi.

**Total:** 52 SP (±156 jam)

| ID | Item Pekerjaan | Ref PRD | PIC | SP | Status | Keterangan |
|----|----------------|---------|-----|----|--------|------------|
| S2-01 | Dosen: persetujuan pengajuan penempatan | FR-08 | BE, FE | 5 | Belum Mulai | |
| S2-02 | Mitra: seleksi dan verifikasi peserta | FR-12 | BE, FE | 5 | Belum Mulai | |
| S2-03 | Mahasiswa: pencatatan kegiatan | FR-05 | BE, FE | 5 | Belum Mulai | |
| S2-04 | Mahasiswa: pengelolaan dokumen dan pengumpulan laporan | FR-06, FR-07 | BE, FE | 5 | Belum Mulai | |
| S2-05 | Dosen: pemantauan, pembimbingan, dan evaluasi | FR-09, FR-10 | BE, FE | 8 | Belum Mulai | |
| S2-06 | Mitra: penilaian mahasiswa | FR-13 | BE, FE | 5 | Belum Mulai | |
| S2-07 | Notifikasi dalam sistem dan email | FR-17 | BE | 5 | Belum Mulai | |
| S2-08 | Admin: pengawasan program, rekapitulasi, dan ekspor laporan | FR-15, FR-16 | BE, FE | 8 | Belum Mulai | Bila kapasitas tidak cukup, ekspor laporan dipindahkan ke akhir minggu 9 |
| S2-09 | Integrasi antarmodul dan perbaikan temuan Sprint 1 | Semua FR | BE, FE | 3 | Belum Mulai | |
| S2-10 | Uji fungsi Sprint 2 dan dokumentasi | Semua item sprint | QA | 3 | Belum Mulai | |

---

## 4. Penjelasan Detail

### 4.1 Peran dan Singkatan PIC
- **PL:** Project Leader
- **SA:** System Analyst
- **BE:** Backend Developer
- **FE:** Frontend Developer
- **QA:** QA & Documentation

### 4.2 Estimasi Waktu
Setiap SP ≈ 3 jam kerja. Dengan kapasitas 5 anggota × 16 jam/minggu = 80 jam/minggu, tiap sprint (2 minggu) menyediakan 160 jam atau ±50 SP. Total kedua sprint: 101 SP (±303 jam), masih di bawah kapasitas 320 jam, sehingga ada ruang untuk perbaikan dan penanganan risiko.

### 4.3 Alur Pelaksanaan Sprint
1. **Sprint Planning** (awal sprint): tim menentukan item yang akan dikerjakan dari product backlog.
2. **Daily Scrum** (15 menit/hari): sinkronisasi progres dan kendala.
3. **Sprint Review** (akhir sprint): increment disajikan kepada perwakilan pengguna, masukan dicatat.
4. **Retrospektif** (akhir sprint): tim mengevaluasi proses dan perbaikan untuk sprint berikutnya.

### 4.4 Status Nyata (dari GitHub)
Semua item S1-01 hingga S2-10 serta tugas QA tambahan (Code Review & UX Assessment, route /register, landing page, kritik UX) telah ditandai **Done** pada repository GitHub kelompok.
