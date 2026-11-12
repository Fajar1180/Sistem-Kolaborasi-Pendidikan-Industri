# Project Charter – Sistem Informasi Kolaborasi Pendidikan dan Industri

**Kelompok:** 6  
**TA:** 2026  
**Tanggal:** 9 Oktober 2026  

---

## 1. Judul dan Latar Belakang

**Judul proyek:** Rancang Bangun Sistem Informasi Kolaborasi Pendidikan dan Industri untuk Meningkatkan Efektivitas Pengelolaan Program Mahasiswa.

**Latar belakang:** Pengelolaan program mahasiswa yang berkaitan dengan dunia industri (kerja praktik, magang, proyek kolaborasi, kunjungan industri, sertifikasi kompetensi) masih dilakukan secara manual dan tersebar di berbagai media. Akibatnya, informasi program sulit diakses, proses persetujuan lambat, data peserta dan mitra tidak terintegrasi, dan pemantauan capaian program kurang akurat. Proyek ini memanfaatkan peluang penyatuan seluruh proses dalam satu sistem berbasis web yang menghubungkan mahasiswa, dosen, mitra industri, dan admin.

---

## 2. Tujuan Proyek (SMART)

| Aspek | Uraian |
|-------|--------|
| **Specific** | Membangun sistem informasi berbasis web dengan empat peran pengguna (mahasiswa, dosen, mitra industri, admin) yang mencakup pendaftaran program, pengajuan penempatan, pencatatan kegiatan, persetujuan, pembimbingan, verifikasi peserta, penilaian, dan rekapitulasi. |
| **Measurable** | Seluruh 17 kebutuhan fungsional pada PRD terimplementasi, seluruh skenario prioritas Must lulus pada UAT, dan dokumentasi analisis, perancangan, pengujian, serta panduan pengguna diserahkan. |
| **Achievable** | Dikerjakan tim lima orang dengan pembagian peran jelas, dukungan anggaran Rp 20.000.000, dan ruang lingkup yang dibatasi pada aplikasi web. |
| **Relevant** | Sejalan dengan kebutuhan perguruan tinggi untuk mempercepat administrasi dan memperkuat kemitraan dengan industri. |
| **Time-bound** | Selesai dalam 12 minggu sejak kick-off, dengan sistem sudah dipublikasikan pada akhir minggu ke-12. |

---

## 3. Ruang Lingkup

**Termasuk (in-scope):** analisis kebutuhan, perancangan, pengembangan empat modul (Mahasiswa, Dosen, Mitra Industri, Admin) beserta autentikasi, hak akses, dan notifikasi, pengujian, deployment ke hosting dan domain, migrasi data awal, pelatihan singkat, dan penyusunan dokumentasi.

**Tidak termasuk (out-of-scope):** aplikasi mobile native, integrasi langsung dengan sistem akademik atau PDDikti, pembayaran dan keuangan program, penerbitan sertifikat elektronik, serta pemeliharaan sistem setelah serah terima.

---

## 4. Deliverable dan Kriteria Keberhasilan

| Deliverable | Kriteria Keberhasilan |
|-------------|----------------------|
| Sistem informasi web yang dapat diakses online | Seluruh modul dapat diakses sesuai peran dan berjalan di lingkungan produksi |
| Dokumen analisis (PRD/SRS) dan perancangan (UML, ERD, UI/UX) | Disahkan sponsor dan dosen pembimbing |
| Dokumen pengujian | Seluruh skenario prioritas Must lulus, temuan kritis ditutup |
| Panduan pengguna tiap peran dan materi pelatihan | Dapat dipakai pengguna tanpa pendampingan |
| Laporan akhir dan berita acara serah terima | Ditandatangani para pihak |

---

## 5. Stakeholder dan Tim

| Pihak | Peran dalam Proyek |
|-------|-------------------|
| Perguruan tinggi (pemberi anggaran) | Sponsor yang mengesahkan Charter dan menerima hasil |
| Dosen pembimbing: Nana Suryana, S.T., M.Kom. | Pengarah dan penilai, ikut menyetujui dokumen |
| Mahasiswa, dosen, mitra industri, dan admin | Pengguna sistem dan peserta UAT |
| Project Leader: Muhammad Fajar Nurjaman | Mengoordinasi tim, jadwal, risiko, dan pelaporan |
| System Analyst: Ikhsan | Analisis kebutuhan, PRD, UML, dan ERD |
| Backend Developer: Ilham Almunawar | Basis data, logika sistem, dan API |
| Frontend Developer: Riphan Romadlon, Fito Zulhian Jabatami | UI/UX dan antarmuka |
| QA & Documentation: Fazna Laisal Ramadhan | Pengujian dan dokumentasi |

---

## 6. Jadwal dan Milestone Utama

Proyek berlangsung 12 minggu dengan urutan: analisis, perancangan, pengembangan, pengujian, dan implementasi. Daftar milestone dan jadwal rinci terdapat pada dokumen terpisah **Jadwal_Proyek_dan_Gantt_Chart.md**.

---

## 7. Sumber Daya dan Biaya

Sumber daya utama: laptop anggota tim, repositori kode, layanan hosting dan domain, perangkat desain dan manajemen proyek.

| No | Komponen | Usulan Biaya (Rp) |
|----|----------|-------------------|
| 1 | Hosting/VPS dan domain | 3.000.000 |
| 2 | Layanan email notifikasi dan pencadangan data | 1.000.000 |
| 3 | Pelaksanaan UAT dan pelatihan pengguna | 2.500.000 |
| 4 | Insentif tim pelaksana | 10.000.000 |
| 5 | Dokumentasi dan pencetakan | 1.000.000 |
| 6 | Dana cadangan | 2.500.000 |
| | **Total** | **20.000.000** |

Sumber pendanaan: anggaran internal perguruan tinggi.

---

## 8. Asumsi, Batasan, dan Risiko

**Asumsi:**
- Perwakilan mahasiswa, dosen, dan mitra industri bersedia dilibatkan dalam wawancara dan UAT.
- Data awal untuk migrasi tersedia paling lambat minggu ke-10.
- Hosting dapat disiapkan sebelum minggu ke-9.

**Batasan:**
- Durasi tetap 12 minggu.
- Anggaran Rp 20.000.000.

**Risiko dan Respons:**

| Risiko | Dampak | Respons |
|--------|--------|---------|
| Keterlambatan pembangunan sistem | Proyek terlampaui jadwal | Penyusunan jadwal dan pemantauan progres secara berkala |
| Perubahan kebutuhan pengguna | Scope creep | Analisis kebutuhan yang matang di awal dan kesepakatan lingkup kerja |
| Gangguan server dan kehilangan data | Sistem tidak dapat diakses | Hosting yang stabil serta pencadangan data secara berkala |
| Rendahnya partisipasi mitra industri dan pengguna | UAT tidak terwakili | Sosialisasi, pelatihan singkat, dan panduan penggunaan sistem |
| Kebocoran atau penyalahgunaan data | Keamanan data terancam | Autentikasi, pembagian hak akses berdasarkan peran, dan validasi input |
| Desain sistem kurang sesuai kebutuhan | Produk tidak diterima pengguna | Evaluasi desain bersama calon pengguna |
| Data awal untuk migrasi terlambat | Implementasi minggu 12 tertunda | Menyiapkan templat impor sejak minggu 6 dan meminta data bertahap |
| Kapasitas sprint terlampaui | Fitur Sprint 2 tidak selesai | Prioritas MoSCoW dan fitur Could dipindahkan ke akhir |
| Waktu UAT terbatas | Perbaikan hasil UAT mepet | Menjadwalkan UAT sejak minggu 9 dan menyiapkan skenario lebih awal |

---

## 9. Persetujuan

| Pihak | Nama | Tanda Tangan |
|-------|------|--------------|
| Dosen Pembimbing | Nana Suryana, S.T., M.Kom. | |
| Ketua Proyek | Muhammad Fajar Nurjaman | |

Ditetapkan di Bandung, 9 Oktober 2026.
