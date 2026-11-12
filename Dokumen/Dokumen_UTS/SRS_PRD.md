# SRS / Product Requirements Document (PRD)

**Sistem Informasi Kolaborasi Pendidikan dan Industri**  
**Kelompok 6 · TA. 2026 · Disusun 9 Oktober 2026**

Dokumen ini menggabungkan **SRS (Software Requirements Specification)** dan **PRD (Product Requirements Document)** sebagai dasar perancangan, pengembangan, dan pengujian sistem. Dokumen ini merupakan deliverable D02 dan disahkan pada milestone M2 (minggu 2).

---

## 1. Ringkasan Produk

Produk yang dibangun adalah sistem informasi berbasis web untuk mengelola kolaborasi pendidikan dan industri, dengan nama kerja **[Nama Produk]**. Sistem menghubungkan mahasiswa, dosen, mitra industri, dan admin dalam satu alur yang terpusat, mulai dari pendaftaran program, pengajuan penempatan, seleksi, pelaksanaan dan pembimbingan, penilaian, hingga rekapitulasi.

**Masalah yang diselesaikan:** proses yang manual dan tersebar, persetujuan yang lambat, data peserta dan mitra yang tidak terintegrasi, serta pemantauan capaian yang kurang akurat.

---

## 2. Sasaran dan Indikator Keberhasilan

| Sasaran | Indikator | Target |
|---------|-----------|--------|
| Seluruh proses terpusat | Persentase program mahasiswa yang tercatat di sistem pada data awal | 100% program yang sedang berjalan |
| Persetujuan lebih cepat | Waktu dosen merespons pengajuan penempatan | Paling lama 3 hari kerja |
| Pemantauan akurat | Kegiatan dan laporan mahasiswa tercatat dan dapat dilihat dosen dan mitra | Seluruh peserta aktif memiliki catatan kegiatan di sistem |
| Pelaporan efisien | Rekap per program, program studi, dan mitra tersedia dari sistem | Tersedia tanpa penyusunan manual |
| Sistem diterima pengguna | Hasil UAT | 100% skenario Must lulus dan kepuasan pengguna minimal 80% |

---

## 3. Pengguna dan Kebutuhan Utamanya

| Peran | Kebutuhan Utama | Hasil yang Diharapkan |
|-------|-----------------|----------------------|
| Mahasiswa | Menemukan program, mendaftar, mengajukan penempatan, mencatat kegiatan, dan mengumpulkan dokumen dan laporan | Proses jelas, status terlihat, tanpa berkas tercecer |
| Dosen | Menyetujui pengajuan, memantau perkembangan, membimbing, dan mengevaluasi | Pemantauan berbasis data dan bimbingan terdokumentasi |
| Mitra Industri | Mempublikasikan peluang, menyeleksi dan memverifikasi peserta, serta menilai mahasiswa | Mendapat peserta yang sesuai dan penilaian yang terstruktur |
| Admin | Mengelola pengguna dan data master, mengawasi seluruh program, dan menyusun rekap | Data terpusat dan laporan cepat dibuat |

---

## 4. Ruang Lingkup Produk

**Termasuk:** empat modul peran beserta autentikasi, hak akses, dan notifikasi, berbasis web dan responsif.

**Tidak termasuk:** aplikasi mobile native, integrasi dengan sistem akademik atau PDDikti, pembayaran, dan penerbitan sertifikat elektronik. Hal-hal ini dicatat sebagai pengembangan lanjutan.

---

## 5. Alur Bisnis Utama

1. Mitra industri mempublikasikan peluang program.
2. Mahasiswa melihat peluang, mendaftar, lalu mengajukan penempatan.
3. Dosen memeriksa pengajuan dan menyetujui, menolak, atau meminta revisi.
4. Pengajuan yang disetujui diteruskan kepada mitra untuk seleksi dan verifikasi.
5. Setelah peserta diterima, program berjalan: mahasiswa mencatat kegiatan, mengelola dokumen, dan mengumpulkan laporan; dosen memantau dan membimbing.
6. Di akhir program, mitra memberi penilaian, dosen melakukan evaluasi, dan admin merekap seluruh data.
7. Notifikasi dikirim setiap kali status penting berubah.

| Status Pengajuan | Makna | Pihak yang Mengubah |
|------------------|-------|---------------------|
| Draf | Belum diajukan, masih dapat diubah | Mahasiswa |
| Diajukan | Menunggu keputusan dosen | Mahasiswa |
| Perlu revisi | Dikembalikan dosen dengan catatan | Dosen |
| Disetujui dosen | Menunggu seleksi mitra | Dosen |
| Ditolak | Tidak dapat dilanjutkan, disertai alasan | Dosen atau mitra |
| Diterima mitra | Peserta terverifikasi | Mitra |
| Berjalan | Pelaksanaan program berlangsung | Sistem |
| Selesai | Penilaian dan evaluasi telah terisi | Sistem |

---

## 6. Kebutuhan Fungsional (FR)

Prioritas memakai MoSCoW: **Must** (wajib ada), **Should** (penting, dapat bergeser), **Could** (tambahan).

| ID | Modul | Kebutuhan | Prioritas | Kriteria Penerimaan |
|----|-------|-----------|-----------|---------------------|
| FR-01 | Umum | Masuk, keluar, dan atur ulang kata sandi | Must | Pengguna terdaftar dapat masuk dengan email dan kata sandi; tautan atur ulang berlaku terbatas; kata sandi tersimpan terenkripsi |
| FR-02 | Umum | Hak akses berdasarkan peran | Must | Setiap halaman dan aksi hanya dapat diakses peran yang berwenang; akses tanpa hak ditolak |
| FR-03 | Mahasiswa | Melihat peluang dan mendaftar program | Must | Mahasiswa dapat mencari dan menyaring peluang, melihat detail, dan mendaftar; status tampil |
| FR-04 | Mahasiswa | Pengajuan penempatan | Must | Mahasiswa memilih program/mitra, mengisi formulir, melampirkan berkas, dan mengajukan; status mengikuti alur |
| FR-05 | Mahasiswa | Pencatatan kegiatan | Must | Catatan berisi tanggal, uraian, dan bukti; dapat diubah sebelum ditinjau; dapat dilihat dosen dan mitra terkait |
| FR-06 | Mahasiswa | Pengelolaan dokumen | Must | Dokumen dapat diunggah, dilihat, diganti, dan dihapus dengan batasan format dan ukuran |
| FR-07 | Mahasiswa | Pengumpulan laporan | Must | Laporan diunggah sesuai tenggat, keterlambatan tertandai, dan dosen dapat meninjau |
| FR-08 | Dosen | Persetujuan pengajuan | Must | Dosen dapat menyetujui, menolak, atau meminta revisi disertai alasan; mahasiswa menerima pemberitahuan |
| FR-09 | Dosen | Pemantauan dan pembimbingan | Must | Dosen melihat daftar mahasiswa bimbingan beserta progres dan catatan kegiatan, serta memberi catatan bimbingan |
| FR-10 | Dosen | Evaluasi mahasiswa | Must | Dosen mengisi penilaian sesuai komponen; nilai tersimpan dan dapat dilihat admin |
| FR-11 | Mitra | Profil mitra dan peluang program | Must | Mitra mengelola profil serta membuat, mengubah, dan menutup peluang (kuota, syarat, periode); peluang tampil bagi mahasiswa setelah dipublikasikan |
| FR-12 | Mitra | Seleksi dan verifikasi peserta | Must | Mitra melihat pelamar, menerima atau menolak, dan memverifikasi peserta; kuota tidak dapat terlampaui |
| FR-13 | Mitra | Penilaian mahasiswa | Must | Mitra mengisi penilaian berdasarkan rubrik hanya untuk peserta yang berstatus berjalan atau selesai |
| FR-14 | Admin | Pengelolaan pengguna dan data master | Must | Admin menambah, mengubah, dan menonaktifkan pengguna serta mengelola data master; data awal dapat diimpor dari berkas CSV |
| FR-15 | Admin | Pengawasan seluruh program | Must | Admin melihat seluruh program, pengajuan, dan penempatan beserta statusnya dengan fitur penyaringan |
| FR-16 | Admin | Rekapitulasi dan laporan | Should | Rekap per program, program studi, dan mitra tampil di layar; ekspor ke Excel atau PDF tersedia |
| FR-17 | Umum | Notifikasi dan komunikasi | Must | Notifikasi dalam sistem muncul pada perubahan status penting dan komentar antar pihak; notifikasi email bersifat tambahan (Could) |

---

## 7. Matriks Hak Akses

✓ = dapat melakukan (membuat, mengubah, atau memutuskan), "lihat" = hanya dapat membaca, kosong = tidak memiliki akses.

| Fitur | Mahasiswa | Dosen | Mitra | Admin |
|-------|-----------|-------|-------|-------|
| Masuk dan kelola akun sendiri | ✓ | ✓ | ✓ | ✓ |
| Peluang program | lihat | lihat | ✓ | lihat |
| Pendaftaran dan pengajuan penempatan | ✓ | lihat | lihat | lihat |
| Persetujuan pengajuan | | ✓ | | lihat |
| Seleksi dan verifikasi peserta | | lihat | ✓ | lihat |
| Catatan kegiatan, dokumen, laporan | ✓ | lihat | lihat | lihat |
| Bimbingan dan evaluasi | lihat | ✓ | | lihat |
| Penilaian mitra | lihat | lihat | ✓ | lihat |
| Data master dan pengguna | | | | ✓ |
| Rekap dan laporan | | lihat | lihat | ✓ |

---

## 8. Kebutuhan Nonfungsional (NFR)

| ID | Aspek | Kebutuhan |
|----|-------|-----------|
| NFR-01 | Keamanan | Kata sandi di-hash, seluruh masukan divalidasi, sesi dilindungi, dan akses lewat HTTPS |
| NFR-02 | Performa | Halaman utama termuat paling lama sekitar 3 detik pada koneksi normal dengan sekitar 100 pengguna bersamaan |
| NFR-03 | Responsif dan kompatibilitas | Tampilan baik pada ponsel, tablet, dan desktop serta pada Chrome, Firefox, Edge, dan Safari versi terbaru |
| NFR-04 | Kegunaan | Tugas utama tiap peran dapat diselesaikan dengan panduan singkat; navigasi konsisten |
| NFR-05 | Ketersediaan dan pencadangan | Pencadangan basis data berkala, minimal harian, dan pemulihan dapat diuji |
| NFR-06 | Jejak audit | Aktivitas penting (persetujuan, perubahan status, perubahan data master) tercatat beserta pelaku dan waktunya |
| NFR-07 | Keterpeliharaan | Kode tersimpan di repositori terkontrol, terstruktur, dan didokumentasikan |
| NFR-08 | Berkas unggahan | Format dan ukuran dibatasi (PDF, JPG, PNG, DOCX, maksimal 5 MB per berkas) dan hanya dapat diakses pihak berwenang |

---

## 9. Data Utama (Entitas)

| Entitas | Keterangan Singkat |
|---------|-------------------|
| Pengguna dan Peran | Akun, peran (mahasiswa, dosen, mitra, admin), dan status aktif |
| Mahasiswa, Dosen, Mitra | Profil masing-masing beserta keterkaitannya dengan pengguna |
| Program | Jenis program, periode, kuota, syarat, mitra penyelenggara, dan status publikasi |
| Pendaftaran dan Penempatan | Hubungan mahasiswa dengan program, status, dosen pembimbing, dan mitra |
| Kegiatan, Dokumen, Laporan | Catatan pelaksanaan, berkas, dan laporan beserta tenggat |
| Bimbingan dan Penilaian | Catatan bimbingan dosen, nilai dosen, dan nilai mitra |
| Notifikasi dan Log Aktivitas | Pemberitahuan sistem dan jejak audit |

---

## 10. Usulan Teknologi

Aplikasi web berbasis framework PHP Laravel dengan basis data MySQL, antarmuka responsif, dan hosting yang mendukung HTTPS serta pencadangan. Keputusan akhir ditetapkan pada paket kerja arsitektur sistem sesuai kemampuan tim dan ketersediaan hosting.

---

## 11. Asumsi, Dependensi, dan Risiko

**Asumsi:** merujuk pada Project Charter (Bagian 2.8) dan KAK (Bagian 1.8).

**Dependensi utama:**
- Ketersediaan perwakilan pengguna untuk wawancara, evaluasi desain, sprint review, dan UAT.
- Ketersediaan data awal untuk migrasi.
- Kesiapan hosting dan domain sebelum minggu 9.

**Risiko:** merujuk pada tabel risiko pada Project Charter.

---

## 12. Keterlacakan Kebutuhan (Traceability)

| Lingkup KAK | Paket WBS | Kebutuhan PRD | Item Sprint |
|-------------|-----------|---------------|-------------|
| Analisis kebutuhan | 2.1–2.3 | Seluruh PRD | Di luar sprint |
| Perancangan sistem | 3.1–3.3 | 7.9 dan 7.10 | Di luar sprint |
| Modul Mahasiswa | 4.4 | FR-03 sampai FR-07 | S1-08, S1-09, S2-03, S2-04 |
| Modul Dosen | 4.5 | FR-08 sampai FR-10 | S2-01, S2-05 |
| Modul Mitra Industri | 4.3 | FR-11 sampai FR-13 | S1-07, S2-02, S2-06 |
| Modul Admin | 4.2 | FR-14 sampai FR-16 | S1-06, S2-08 |
| Autentikasi, hak akses, notifikasi | 4.1, 4.6 | FR-01, FR-02, FR-17 | S1-03, S1-04, S2-07 |
| Pengujian sistem | 5.1–5.4 | Semua FR dan NFR | S1-10, S2-10 |
| Implementasi sistem | 6.1–6.4 | NFR-05, FR-14 (impor data) | Di luar sprint |
