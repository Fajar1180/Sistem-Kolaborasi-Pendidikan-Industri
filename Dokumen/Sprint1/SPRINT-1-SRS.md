# Software Requirements Specification (SRS) - Sprint 1
**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri  
**Sprint:** 1 (Minggu 6-7)  
**Versi:** 1.0  
**Tanggal:** 2026-10-13  

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen ini menjabarkan spesifikasi kebutuhan teknis untuk **Sprint 1** yang mencakup autentikasi, hak akses, data master, peluang program, pendaftaran, dan pengajuan penempatan.

### 1.2 Cakupan
Sprint 1 fokus pada fondasi sistem yang memungkinkan:
- Pengguna dapat masuk sesuai perannya
- Admin dapat mengelola data master
- Mitra dapat mempublikasikan peluang program
- Mahasiswa dapat mendaftar program serta mengajukan penempatan

### 1.3 Definisi, Akronim, dan Singkatan
- **FR**: Functional Requirement (Kebutuhan Fungsional)
- **NFR**: Non-Functional Requirement (Kebutuhan Non-Fungsional)
- **SP**: Story Point
- **PRD**: Product Requirements Document
- **SRS**: Software Requirements Specification

## 2. Kebutuhan Fungsional Sprint 1

Berikut adalah kebutuhan fungsional yang termasuk dalam Sprint 1 berdasarkan PRD dan Sprint Backlog:

| ID | Modul | Kebutuhan | Prioritas | Kriteria Penerimaan |
|----|-------|-----------|-----------|---------------------|
| FR-01 | Umum | Masuk, keluar, dan atur ulang kata sandi | Must | Pengguna terdaftar dapat masuk dengan email dan kata sandi; tautan atur ulang kata sandi berlaku terbatas; kata sandi tersimpan dalam bentuk terenkripsi |
| FR-02 | Umum | Hak akses berdasarkan peran | Must | Setiap halaman dan aksi hanya dapat diakses peran yang berwenang; akses tanpa hak ditolak |
| FR-03 | Mahasiswa | Melihat peluang dan mendaftar program | Must | Mahasiswa dapat mencari dan menyaring peluang, melihat detail, dan mendaftar; status pendaftaran tampil |
| FR-04 | Mahasiswa | Pengajuan penempatan | Must | Mahasiswa memilih program atau mitra, mengisi formulir, melampirkan berkas, dan mengajukan; status mengikuti alur pada PRD 7.5 |
| FR-11 | Mitra | Profil mitra dan peluang program | Must | Mitra mengelola profil serta membuat, mengubah, dan menutup peluang (kuota, syarat, periode); peluang tampil bagi mahasiswa setelah dipublikasikan |
| FR-14 | Admin | Pengelolaan pengguna dan data master | Must | Admin menambah, mengubah, dan menonaktifkan pengguna serta mengelola data master; data awal dapat diimpor dari berkas CSV |

## 3. Kebutuhan Non-Fungsional Sprint 1

| ID | Aspek | Kebutuhan |
|----|-------|-----------|
| NFR-01 | Keamanan | Kata sandi di-hash, seluruh masukan divalidasi, sesi dilindungi, dan akses lewat HTTPS |
| NFR-02 | Performa | Halaman utama termuat paling lama sekitar 3 detik pada koneksi normal dengan sekitar 100 pengguna bersamaan |
| NFR-03 | Responsif dan kompatibilitas | Tampilan baik pada layar ponsel, tablet, dan desktop serta pada Chrome, Firefox, Edge, dan Safari versi terbaru |
| NFR-04 | Kegunaan | Tugas utama tiap peran dapat diselesaikan dengan panduan singkat; navigasi konsisten |
| NFR-05 | Ketersediaan dan pencadangan | Pencadangan basis data berkala, minimal harian, dan pemulihan dapat diuji |
| NFR-06 | Jejak audit | Aktivitas penting (persetujuan, perubahan status, perubahan data master) tercatat beserta pelaku dan waktunya |
| NFR-07 | Keterpeliharaan | Kode tersimpan di repositori terkontrol, terstruktur, dan didokumentasikan |
| NFR-08 | Berkas unggahan | Format dan ukuran dibatasi (PDF, JPG, PNG, DOCX, maksimal 5 MB per berkas) dan hanya dapat diakses pihak berwenang |

## 4. Data Utama yang Terlibat Sprint 1

| Entitas | Keterangan singkat |
|---------|-------------------|
| Pengguna dan Peran | Akun, peran (mahasiswa, dosen, mitra, admin), dan status aktif |
| Mahasiswa, Dosen, Mitra | Profil masing-masing beserta keterkaitannya dengan pengguna |
| Program | Jenis program, periode, kuota, syarat, mitra penyelenggara, dan status publikasi |
| Pendaftaran dan Penempatan | Hubungan mahasiswa dengan program, status, dosen pembimbing, dan mitra |
| Notifikasi dan Log Aktivitas | Pemberitahuan sistem dan jejak audit |

## 5. Asumsi dan Ketergantungan

1. Lingkungan pengembangan telah siap (Laragon, PHP 8.1, Composer, Node.js, npm)
2. Basis data MySQL tersedia dan dapat diakses
3. Tim memiliki akses ke repositori GitHub
4. Desain UI/UX dasar telah tersedia dari Figma
5. Data awal untuk seeding telah disiapkan oleh tim backend

## 6. Kriteria Penerimaan Sprint 1 (Definition of Done)

Item dianggap **SELESAI** bila:
1. Kodenya telah ditinjau dan digabungkan ke cabang utama (main)
2. Memenuhi kriteria penerimaan pada PRD
3. Lulus uji fungsi oleh QA
4. Tampil baik pada layar ponsel dan desktop
5. Ter catat dalam dokumentasi sprint

## 7. Referensi

1. [Dokumen Perencanaan Proyek – Sistem Informasi Kolaborasi Pendidikan dan Industri (Kelompok 6).md](./../Dokumen%20Perencanaan%20Proyek%20%E2%80%93%20Sistem%20Informasi%20Kolaborasi%20Pendidikan%20dan%20Industri%20%28Kelompok%206%29.md)
2. [Figma Design](https://www.figma.com/make/gFlzRXZaxdrvPFI3IEfn8l/Web-based-Academic-Management-System?code-node-id=0-6&p=f&t=gCkDtI9hUTDUNVUo-0&fullscreen=1)
3. Laravel Documentation: https://laravel.com/docs