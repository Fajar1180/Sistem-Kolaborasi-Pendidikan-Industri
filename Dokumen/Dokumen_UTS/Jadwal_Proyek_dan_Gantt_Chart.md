# Jadwal Proyek dan Gantt Chart – Sistem Informasi Kolaborasi Pendidikan dan Industri

**Kelompok:** 6  
**TA:** 2026  
**Total durasi:** 12 Minggu  
**Minggu 1 dimulai pada:** [tanggal kick-off akan diisi setelah ditetapkan]

---

## 1. Ringkasan Tahapan (KAK)

| No | Kegiatan | Durasi | Minggu | PIC |
|----|----------|--------|--------|-----|
| 1 | Analisis Kebutuhan | 2 Minggu | 1–2 | SA |
| 2 | Perancangan Sistem | 3 Minggu | 3–5 | SA |
| 3 | Pengembangan Sistem | 4 Minggu | 6–9 | BE, FE |
| 4 | Pengujian Sistem | 2 Minggu | 10–11 | QA |
| 5 | Implementasi dan Pelatihan | 1 Minggu | 12 | PL |

---

## 2. Gantt Chart (Tabel Mingguan)

Tanda **■** menunjukkan minggu pelaksanaan kegiatan.

| Kegiatan | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | PIC |
|----------|---|---|---|---|---|---|---|---|---|----|----|----|-----|
| Pemantauan dan pelaporan progres | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | PL |
| Project Charter, WBS, jadwal | ■ | | | | | | | | | | | | PL |
| Identifikasi kebutuhan pengguna | ■ | | | | | | | | | | | | SA |
| Analisis alur program berjalan | ■ | ■ | | | | | | | | | | | SA |
| Penetapan fitur, data, hak akses (PRD) | | ■ | | | | | | | | | | | SA, PL |
| Arsitektur dan alur kolaborasi | | | ■ | | | | | | | | | | SA, BE |
| Basis data dan pemodelan | | | ■ | ■ | | | | | | | | | SA, BE |
| Desain UI/UX dan evaluasi desain | | | | ■ | ■ | | | | | | | | FE |
| Sprint 1: fondasi, data master, pendaftaran, pengajuan | | | | | | ■ | ■ | | | | | | BE, FE |
| Sprint 2: dosen, mitra, kegiatan, notifikasi, rekap | | | | | | | | ■ | ■ | | | | BE, FE |
| Persiapan hosting dan staging | | | | | | | | | ■ | ■ | | | BE |
| Uji fungsi dan alur antarperan | | | | | | | | | | ■ | | | QA |
| Uji perangkat, performa, keamanan | | | | | | | | | | ■ | ■ | | QA, FE, BE |
| UAT dan perbaikan | | | | | | | | | | | ■ | | QA, PL |
| Deployment produksi dan migrasi data | | | | | | | | | | | | ■ | BE, SA |
| Pelatihan dan panduan pengguna | | | | | | | | | | | | ■ | QA, SA |
| Publikasi, laporan akhir, serah terima | | | | | | | | | | | | ■ | PL |

---

## 3. Jalur Kritis

Urutan utama (jalur kritis): **Analisis → PRD disahkan → Desain disetujui → Sprint 1 → Sprint 2 → Pengujian → UAT → Deployment.**

Keterlambatan pada salah satu titik langsung menggeser tanggal selesai. Oleh karena itu:
- Pengesahan PRD pada minggu 2 dijaga ketat.
- Persetujuan desain pada minggu 5 dijaga ketat.
- Hosting dan staging disiapkan sejak minggu 9 agar minggu 12 tidak terlalu padat.
- Templat impor data awal dibuat selama pengembangan.
- Skenario UAT disusun sebelum minggu 11.

---

## 4. Milestone

| Kode | Milestone | Minggu | Bukti Pencapaian |
|------|-----------|--------|------------------|
| M1 | Project Charter disetujui | 1 | Tanda tangan sponsor dan dosen pembimbing |
| M2 | PRD/SRS disahkan | 2 | Persetujuan tertulis atas D02 |
| M3 | Desain sistem dan prototipe disetujui | 5 | Persetujuan D03 dan D04 |
| M4 | Sprint 1 selesai | 7 | Sprint review dan increment D05 |
| M5 | Sprint 2 selesai | 9 | Sprint review dan increment D06 |
| M6 | UAT dinyatakan lulus | 11 | Berita acara UAT |
| M7 | Sistem berhasil di-deploy dan dipublikasikan | 12 | Sistem dapat diakses melalui domain |
| M8 | Serah terima proyek | 12 | Berita acara serah terima |

---

## 5. Referensi GitHub

Semua item sprint dalam jadwal ini telah dilaksanakan dan ditandai Done pada repository:
https://github.com/Fajar1180/Sistem-Kolaborasi-Pendidikan-Industri/issues?q=is%3Aissue+state%3Aclosed

Roadmap proyek:
https://github.com/users/Fajar1180/projects/15/views/4
