# PRD (Product Requirements Document) - Sistem Informasi Kolaborasi Pendidikan dan Industri
**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri  
**Versi:** 1.0  
**Tanggal:** 2026-10-13  

> **Catatan:** PRD lengkap (Bagian 7 dari Dokumen Perencanaan Proyek) menjadi dasar seluruh pengembangan.  
> Dokumen ini adalah ringkasan terstruktur untuk akses cepat tim teknis.

## 1. Ringkasan Produk
Sistem informasi berbasis web untuk mengelola kolaborasi pendidikan dan industri, menghubungkan **mahasiswa, dosen, mitra industri, dan admin** dalam satu alur terpusat: pendaftaran program → pengajuan penempatan → persetujuan dosen → seleksi mitra → pelaksanaan (kegiatan, dokumen, laporan) → penilaian → rekapitulasi.

**Masalah yang diselesaikan:** proses manual, tersebar, persetujuan lambat, data tidak terintegrasi, pemantauan capaian kurang akurat.

## 2. Pengguna dan Kebutuhan Utama
| Peran | Kebutuhan utama |
|-------|------------------|
| Mahasiswa | Cari program, daftar, ajukan penempatan, catat kegiatan, upload dokumen/laporan |
| Dosen | Setujui pengajuan, pantau & bimbing, evaluasi mahasiswa |
| Mitra | Publikasi peluang, seleksi & verifikasi peserta, nilai mahasiswa |
| Admin | Kelola pengguna & data master, awasi seluruh program, rekap & laporan |

## 3. Ruang Lingkup
**Termasuk:** 4 modul peran + autentikasi + hak akses + notifikasi, web responsif.  
**Tidak termasuk:** app mobile native, integrasi akademik/PDDikti, pembayaran, sertifikat elektronik.

## 4. Alur Bisnis Utama (Status Pengajuan Penempatan)
| Status | Makna | Pihak pengubah |
|--------|-------|----------------|
| Draf | Belum diajukan, masih bisa diubah | Mahasiswa |
| Diajukan | Menunggu keputusan dosen | Mahasiswa |
| Perlu revisi | Dikembalikan dosen dengan catatan | Dosen |
| Disetujui dosen | Menunggu seleksi mitra | Dosen |
| Ditolak | Tidak bisa dilanjutkan, disertai alasan | Dosen/Mitra |
| Diterima mitra | Peserta terverifikasi | Mitra |
| Berjalan | Pelaksanaan program berlangsung | Sistem |
| Selesai | Penilaian dan evaluasi lengkap | Sistem |

## 5. Kebutuhan Fungsional (MoSCoW)
| ID | Modul | Kebutuhan | Prioritas | Sprint |
|----|-------|-----------|-----------|--------|
| FR-01 | Umum | Masuk, keluar, atur ulang kata sandi | Must | S1 |
| FR-02 | Umum | Hak akses berdasarkan peran | Must | S1 |
| FR-03 | Mahasiswa | Melihat peluang & daftar program | Must | S1 |
| FR-04 | Mahasiswa | Pengajuan penempatan | Must | S1 |
| FR-05 | Mahasiswa | Pencatatan kegiatan | Must | S2 |
| FR-06 | Mahasiswa | Pengelolaan dokumen | Must | S2 |
| FR-07 | Mahasiswa | Pengumpulan laporan | Must | S2 |
| FR-08 | Dosen | Persetujuan pengajuan | Must | S2 |
| FR-09 | Dosen | Pemantauan & pembimbingan | Must | S2 |
| FR-10 | Dosen | Evaluasi mahasiswa | Must | S2 |
| FR-11 | Mitra | Profil & publikasi peluang program | Must | S1 |
| FR-12 | Mitra | Seleksi & verifikasi peserta | Must | S2* |
| FR-13 | Mitra | Penilaian mahasiswa | Must | S2 |
| FR-14 | Admin | Pengelolaan pengguna & data master | Must | S1 |
| FR-15 | Admin | Pengawasan seluruh program | Must | S2 |
| FR-16 | Admin | Rekapitulasi & laporan (ekspor) | Should | S2 |
| FR-17 | Umum | Notifikasi & komunikasi | Must | S2 |

*FR-12 masuk Sprint 1 di backlog (S1-07) untuk bagian profil/peluang, tetapi seleksi & verifikasi peserta menjadi Sprint 2 (S2-02).

## 6. Kebutuhan Non-Fungsional (NFR)
| ID | Aspek | Kebutuhan |
|----|-------|-----------|
| NFR-01 | Keamanan | Hash kata sandi, validasi input, sesi aman, HTTPS |
| NFR-02 | Performa | Halaman utama ≤ 3 detik untuk ~100 pengguna bersamaan |
| NFR-03 | Responsif | Layar ponsel, tablet, desktop; Chrome/Firefox/Edge/Safari terbaru |
| NFR-04 | Kegunaan | Tugas utama tiap peran selesai dengan panduan singkat |
| NFR-05 | Ketersediaan | Backup DB harian, pemulihan teruji |
| NFR-06 | Jejak audit | Aktivitas penting tercatat (pelaku + waktu) |
| NFR-07 | Keterpeliharaan | Kode terkontrol di repositori, terstruktur, terdokumentasi |
| NFR-08 | Berkas unggahan | Format: PDF, JPG, PNG, DOCX; maks 5 MB; akses hanya pihak berwenang |

## 7. Matriks Hak Akses
| Fitur | Mahasiswa | Dosen | Mitra | Admin |
|-------|-----------|-------|-------|-------|
| Peluang program | lihat | lihat | ✓ | lihat |
| Pendaftaran & pengajuan penempatan | ✓ | lihat | lihat | lihat |
| Persetujuan pengajuan | - | ✓ | - | lihat |
| Seleksi & verifikasi peserta | - | lihat | ✓ | lihat |
| Data master & pengguna | - | - | - | ✓ |
| Rekap & laporan | - | lihat | lihat | ✓ |

## 8. Data Utama
Pengguna & Peran · Mahasiswa/Dosen/Mitra · Program · Pendaftaran & Penempatan · Kegiatan/Dokumen/Laporan · Bimbingan & Penilaian · Notifikasi & Log Aktivitas

## 9. Usulan Teknologi
- **Backend:** PHP Laravel + MySQL
- **Frontend:** Blade + Tailwind CSS (responsif)
- **Server lokal:** Laragon
- **Version control:** Git + GitHub (branch `develop` → `main`)

## 10. Referensi
1. Dokumen Perencanaan Proyek – Sistem Informasi Kolaborasi Pendidikan dan Industri (Kelompok 6).md — Bagian 7 (PRD lengkap)
2. Figma Design: https://www.figma.com/make/gFlzRXZaxdrvPFI3IEfn8l/Web-based-Academic-Management-System?code-node-id=0-6&p=f&t=gCkDtI9hUTDUNVUo-0&fullscreen=1