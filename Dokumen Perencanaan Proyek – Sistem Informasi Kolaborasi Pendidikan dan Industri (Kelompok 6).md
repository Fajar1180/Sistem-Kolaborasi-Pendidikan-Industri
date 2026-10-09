# Dokumen Perencanaan Proyek: Sistem Informasi Kolaborasi Pendidikan dan Industri

Kelompok 6 · TA. 2026 · Disusun 9 Oktober 2026

Dokumen ini menghimpun seluruh kebutuhan perencanaan proyek dalam satu berkas yang saling selaras. Kerangka Acuan Kerja (KAK) menjadi dasar, kemudian dilengkapi dengan Project Charter, Work Breakdown Structure (WBS), deliverable dan milestone, pemilihan metodologi dan estimasi, jadwal beserta Gantt Chart, Sprint Backlog, serta Product Requirements Document (PRD). Isian dalam kurung siku, seperti nama anggota, NPM, dan tanggal, perlu dilengkapi kelompok sebelum dikumpulkan. Angka anggaran, estimasi, dan kapasitas sprint bersifat usulan awal yang dapat disesuaikan dengan kesepakatan tim.

| Bagian | Dokumen | Fungsi |
| --- | --- | --- |
| 1 | Kerangka Acuan Kerja (KAK) | Dasar dan batasan kegiatan |
| 2 | Project Charter | Pengesahan proyek dan kewenangan tim |
| 3 | Work Breakdown Structure (WBS) | Pemecahan pekerjaan menjadi paket kerja |
| 4 | Deliverable, Milestone, Metodologi, dan Estimasi | Hasil kerja, tonggak, pendekatan, dan dasar perkiraan waktu |
| 5 | Jadwal Proyek dan Gantt Chart | Urutan dan durasi pekerjaan selama 12 minggu |
| 6 | Sprint Backlog | Daftar pekerjaan tiap sprint pengembangan |
| 7 | Product Requirements Document (PRD) | Kebutuhan produk secara rinci |

## 1. Kerangka Acuan Kerja (KAK)

**Rancang Bangun Sistem Informasi Kolaborasi Pendidikan dan Industri untuk Meningkatkan Efektivitas Pengelolaan Program Mahasiswa – TA. 2026**

### 1.1 Latar Belakang

Program mahasiswa yang berkaitan dengan dunia industri, seperti kerja praktik, magang, proyek kolaborasi, kunjungan industri, dan sertifikasi kompetensi, merupakan bagian penting dari proses pendidikan di perguruan tinggi. Melalui program tersebut, mahasiswa memperoleh kesempatan menerapkan ilmu yang dipelajari di kelas pada kebutuhan nyata di lapangan, sementara industri mendapatkan akses terhadap calon tenaga kerja yang sesuai dengan kebutuhannya. Kolaborasi pendidikan dan industri yang berjalan baik akan meningkatkan relevansi lulusan dengan kebutuhan dunia kerja.

Pada praktiknya, pengelolaan program mahasiswa tersebut masih banyak dilakukan secara manual dan tersebar di berbagai media, mulai dari pendaftaran program, pengumpulan berkas, pencocokan mahasiswa dengan mitra industri, pemantauan pelaksanaan, hingga evaluasi dan pelaporan hasil. Kondisi ini menyebabkan informasi program sulit diakses, proses persetujuan berjalan lambat, data peserta dan mitra tidak terintegrasi, serta pemantauan capaian program kurang akurat. Komunikasi antara mahasiswa, dosen, dan mitra industri pun sering terputus sehingga efektivitas pengelolaan program menurun.

Perkembangan teknologi informasi membuka peluang untuk mengelola seluruh proses tersebut dalam satu sistem informasi berbasis web yang menghubungkan mahasiswa, dosen, mitra industri, dan admin. Dengan sistem yang terpadu, kegiatan pendaftaran, seleksi, penempatan, pemantauan, komunikasi, dan evaluasi program dapat dilakukan secara terpusat, transparan, dan terdokumentasi dengan baik, sehingga keputusan pengelolaan dapat diambil berdasarkan data yang akurat. Oleh karena itu, diperlukan kegiatan Rancang Bangun Sistem Informasi Kolaborasi Pendidikan dan Industri yang mampu mempercepat proses administrasi, mempermudah koordinasi antar pihak, serta memperkuat kemitraan yang berkelanjutan antara perguruan tinggi dan mitra industri.

### 1.2 Maksud dan Tujuan

a) Merancang dan membangun sistem informasi kolaborasi pendidikan dan industri berbasis web yang menghubungkan mahasiswa, dosen, mitra industri, dan admin.

b) Mendigitalkan pengelolaan program mahasiswa, mulai dari pendaftaran, pengajuan penempatan, pelaporan kegiatan, hingga pengelolaan dokumen oleh mahasiswa.

c) Mempermudah dosen dalam melakukan persetujuan, pembimbingan, pemantauan perkembangan, dan evaluasi mahasiswa dalam program yang diikuti.

d) Mempermudah mitra industri dalam mempublikasikan peluang program, menyeleksi dan memverifikasi peserta, serta memberikan penilaian terhadap mahasiswa.

e) Menyediakan pengelolaan data terpusat oleh admin untuk mendukung pengawasan, rekapitulasi, dan pelaporan seluruh program mahasiswa.

f) Meningkatkan efektivitas, transparansi, dan akuntabilitas pengelolaan program mahasiswa.

g) Memperkuat kolaborasi dan keberlanjutan kemitraan antara perguruan tinggi dan dunia industri.

### 1.3 Sumber Pendanaan

Sumber pendanaan kegiatan ini berasal dari anggaran internal perguruan tinggi sebesar Rp. 20.000.000 yang dialokasikan untuk pengembangan sistem informasi akademik dan kerja sama industri. Dana tersebut digunakan untuk mendukung proses analisis kebutuhan, perancangan, pembangunan, pengujian, hingga implementasi sistem. Rincian penggunaan dana diuraikan pada Bagian 2 (Project Charter).

### 1.4 Lingkup Kegiatan

**1. Analisis Kebutuhan**

a) Mengidentifikasi kebutuhan pengguna (mahasiswa, dosen, mitra industri, dan admin) dalam pengelolaan program mahasiswa.

b) Menganalisis alur pengelolaan program mahasiswa yang sedang berjalan, mulai dari pendaftaran hingga evaluasi.

c) Menentukan fitur, kebutuhan data, dan hak akses pada setiap modul sistem.

**2. Perancangan Sistem**

a) Merancang arsitektur sistem dan alur kolaborasi antar modul dan antar pihak.

b) Merancang basis data dan pemodelan sistem (use case, activity diagram, ERD).

c) Mendesain tampilan antarmuka (UI/UX) dan navigasi sistem yang mudah digunakan.

**3. Pengembangan Sistem**

a) Modul Mahasiswa: pendaftaran program, pengajuan penempatan, pencatatan kegiatan, pengelolaan dokumen, dan pengumpulan laporan.

b) Modul Dosen: persetujuan pengajuan, pemantauan perkembangan, pembimbingan, dan evaluasi mahasiswa.

c) Modul Mitra Industri: pengelolaan profil dan peluang program, seleksi dan verifikasi peserta, serta penilaian mahasiswa.

d) Modul Admin: pengelolaan pengguna dan data master, pengawasan seluruh program, serta rekapitulasi data dan laporan.

e) Pengembangan sistem autentikasi, pembagian hak akses berdasarkan peran pengguna, serta fitur notifikasi dan komunikasi antar pihak.

**4. Pengujian Sistem**

a) Pengujian fungsi setiap modul dan alur kolaborasi antar peran pengguna.

b) Pengujian tampilan pada berbagai perangkat dan peramban.

c) Pengujian performa dan keamanan sistem.

d) Uji penerimaan pengguna (User Acceptance Test) bersama perwakilan mahasiswa, dosen, dan mitra industri.

**5. Implementasi Sistem**

a) Mengunggah sistem ke hosting dan menghubungkan dengan domain.

b) Migrasi data awal (data mahasiswa, dosen, mitra industri, dan program yang sedang berjalan).

c) Pelatihan singkat kepada pengguna dan penyusunan panduan penggunaan sistem.

d) Publikasi sistem agar dapat diakses oleh seluruh pengguna.

### 1.5 Personil

| Posisi | Nama - NPM |
| --- | --- |
| Project Leader | Muhammad Fajar Nurjaman : 20241320059 |
| System Analyst | Ikhsan : 2024130083 |
| Backend Developer | Ilham Almunawar : 20241320075 |
| Frontend Developer | Riphan Romadlon : 20241320064 Fito Zulhian Jabatami : 20241320083 |
| QA & Documentation | Fazna Laisal Ramadhan : 20241320081 |

### 1.6 Jadwal Tahapan Pelaksanaan (Total 12 Minggu)

| No | Kegiatan | Waktu | Pelaksanaan |
| --- | --- | --- | --- |
| 1 | Analisis Kebutuhan | 2 Minggu | Minggu 1–2 |
| 2 | Perancangan Sistem | 3 Minggu | Minggu 3–5 |
| 3 | Pengembangan Sistem | 4 Minggu | Minggu 6–9 |
| 4 | Pengujian Sistem | 2 Minggu | Minggu 10–11 |
| 5 | Implementasi dan Pelatihan | 1 Minggu | Minggu 12 |

### 1.7 Keluaran

1. Sistem informasi kolaborasi pendidikan dan industri berbasis web yang dapat diakses secara online oleh mahasiswa, dosen, mitra industri, dan admin.
2. Modul pendaftaran program, pengajuan penempatan, pencatatan kegiatan, pengelolaan dokumen dan laporan, persetujuan, pemantauan, pembimbingan, verifikasi, dan penilaian yang berfungsi sesuai kebutuhan.
3. Tampilan sistem yang mudah digunakan (user friendly) dan responsif di berbagai perangkat.
4. Dokumentasi sistem (analisis, perancangan, pengujian) dan panduan penggunaan untuk setiap peran pengguna.
5. Sistem yang meningkatkan efektivitas pengelolaan program mahasiswa serta mempermudah kolaborasi dan pemantauan antara perguruan tinggi dan mitra industri.

### 1.8 Risiko dan Mitigasi

| No | Risiko | Mitigasi |
| --- | --- | --- |
| 1 | Keterlambatan pembangunan sistem | Penyusunan jadwal dan pemantauan progres secara berkala |
| 2 | Perubahan kebutuhan pengguna | Analisis kebutuhan yang matang di awal dan kesepakatan lingkup kerja |
| 3 | Gangguan server dan kehilangan data | Hosting yang stabil serta pencadangan data secara berkala |
| 4 | Rendahnya partisipasi mitra industri dan pengguna | Sosialisasi, pelatihan singkat, dan panduan penggunaan sistem |
| 5 | Kebocoran atau penyalahgunaan data | Autentikasi, pembagian hak akses berdasarkan peran, dan validasi input |
| 6 | Desain sistem kurang sesuai dengan kebutuhan | Evaluasi desain bersama calon pengguna |

Ditetapkan di Bandung

Tanggal  9 Oktober 2026

Project Manager

(Muhammad Fajar Nurjaman)

## 2. Project Charter

Project Charter ini mengesahkan proyek dan memberi wewenang kepada tim untuk memulai pekerjaan. Dokumen ini menjadi acuan bersama antara sponsor, dosen pembimbing, dan tim sebelum WBS serta jadwal dijalankan.

### 2.1 Judul dan Latar Belakang

Judul proyek adalah Rancang Bangun Sistem Informasi Kolaborasi Pendidikan dan Industri untuk Meningkatkan Efektivitas Pengelolaan Program Mahasiswa. Masalah yang ditangani adalah pengelolaan program mahasiswa yang masih manual, tersebar di berbagai media, dan tidak terintegrasi, sehingga persetujuan lambat, data peserta dan mitra terpisah, serta pemantauan capaian kurang akurat. Peluang yang dimanfaatkan adalah penyatuan seluruh proses dalam satu sistem berbasis web yang menghubungkan mahasiswa, dosen, mitra industri, dan admin.

### 2.2 Tujuan Proyek (SMART)

| Aspek | Uraian |
| --- | --- |
| Specific | Membangun sistem informasi berbasis web dengan empat peran pengguna yang mencakup pendaftaran program, pengajuan penempatan, pencatatan kegiatan, persetujuan, pembimbingan, verifikasi peserta, penilaian, dan rekapitulasi. |
| Measurable | Seluruh 17 kebutuhan fungsional pada PRD terimplementasi, seluruh skenario prioritas Must lulus pada UAT, dan dokumentasi analisis, perancangan, pengujian, serta panduan pengguna diserahkan. |
| Achievable | Dikerjakan tim lima orang dengan pembagian peran jelas, dukungan anggaran Rp. 20.000.000, dan ruang lingkup yang dibatasi pada aplikasi web. |
| Relevant | Sejalan dengan kebutuhan perguruan tinggi untuk mempercepat administrasi dan memperkuat kemitraan dengan industri. |
| Time-bound | Selesai dalam 12 minggu sejak kick-off, dengan sistem sudah dipublikasikan pada akhir minggu ke-12. |

### 2.3 Ruang Lingkup

**Termasuk (in-scope):** analisis kebutuhan, perancangan, pengembangan empat modul (Mahasiswa, Dosen, Mitra Industri, Admin) beserta autentikasi, hak akses, dan notifikasi, pengujian, deployment ke hosting dan domain, migrasi data awal, pelatihan singkat, dan penyusunan dokumentasi.

**Tidak termasuk (out-of-scope):** aplikasi mobile native, integrasi langsung dengan sistem akademik atau PDDikti, pembayaran dan keuangan program, penerbitan sertifikat elektronik, serta pemeliharaan sistem setelah serah terima.

### 2.4 Deliverable dan Kriteria Keberhasilan

| Deliverable | Kriteria keberhasilan |
| --- | --- |
| Sistem informasi web yang dapat diakses online | Seluruh modul dapat diakses sesuai peran dan berjalan di lingkungan produksi |
| Dokumen analisis (PRD/SRS) dan perancangan (UML, ERD, UI/UX) | Disahkan sponsor dan dosen pembimbing |
| Dokumen pengujian | Seluruh skenario prioritas Must lulus, temuan kritis ditutup |
| Panduan pengguna tiap peran dan materi pelatihan | Dapat dipakai pengguna tanpa pendampingan |
| Laporan akhir dan berita acara serah terima | Ditandatangani para pihak |

### 2.5 Stakeholder dan Tim

| Pihak | Peran dalam proyek |
| --- | --- |
| Perguruan tinggi (pemberi anggaran) | Sponsor yang mengesahkan Charter dan menerima hasil |
| Dosen pembimbing: \[Nama Dosen\] | Pengarah dan penilai, ikut menyetujui dokumen |
| Mahasiswa, dosen, mitra industri, dan admin | Pengguna sistem dan peserta UAT |
| Project Leader: \[Nama\] | Mengoordinasi tim, jadwal, risiko, dan pelaporan |
| System Analyst: \[Nama\] | Analisis kebutuhan, PRD, UML, dan ERD |
| Backend Developer: \[Nama\] | Basis data, logika sistem, dan API |
| Frontend Developer: \[Nama\] | UI/UX dan antarmuka |
| QA & Documentation: \[Nama\] | Pengujian dan dokumentasi |

### 2.6 Jadwal dan Milestone Utama

Proyek berlangsung 12 minggu dengan urutan analisis, perancangan, pengembangan, pengujian, dan implementasi. Daftar milestone dan jadwal rinci terdapat pada Bagian 4 dan Bagian 5.

### 2.7 Sumber Daya dan Biaya

Sumber daya utama berupa laptop anggota tim, repositori kode, layanan hosting dan domain, serta perangkat desain dan manajemen proyek. Usulan alokasi anggaran Rp. 20.000.000 adalah sebagai berikut.

| No | Komponen | Usulan Biaya (Rp) |
| --- | --- | --- |
| 1 | Hosting/VPS dan domain | 3.000.000 |
| 2 | Layanan email notifikasi dan pencadangan data | 1.000.000 |
| 3 | Pelaksanaan UAT dan pelatihan pengguna | 2.500.000 |
| 4 | Insentif tim pelaksana | 10.000.000 |
| 5 | Dokumentasi dan pencetakan | 1.000.000 |
| 6 | Dana cadangan | 2.500.000 |
|  | **Total** | **20.000.000** |

### 2.8 Asumsi, Batasan, dan Risiko

Asumsi proyek adalah perwakilan mahasiswa, dosen, dan mitra industri bersedia dilibatkan dalam wawancara dan UAT, data awal untuk migrasi tersedia paling lambat minggu ke-10, dan hosting dapat disiapkan sebelum minggu ke-9. Batasan proyek adalah durasi tetap 12 minggu dan anggaran Rp. 20.000.000. Risiko awal dan responsnya mengikuti tabel pada Bagian 1.8, dengan tambahan berikut.

| Risiko tambahan | Dampak | Respons |
| --- | --- | --- |
| Data awal untuk migrasi terlambat atau tidak rapi | Implementasi minggu 12 tertunda | Menyiapkan templat impor sejak minggu 6 dan meminta data bertahap |
| Kapasitas sprint terlampaui | Fitur Sprint 2 tidak selesai | Prioritas MoSCoW dan fitur Could dipindahkan ke akhir |
| Waktu UAT terbatas karena jadwal mitra | Perbaikan hasil UAT mepet | Menjadwalkan UAT sejak minggu 9 dan menyiapkan skenario lebih awal |

### 2.9 Persetujuan

| Pihak | Nama |
| --- | --- |
| Dosen Pembimbing | Nana Suryana, S.T., M.Kom. |
| Ketua Proyek | Muhammad Fajar Nurjaman |

## 3. Work Breakdown Structure (WBS)

WBS disusun berorientasi deliverable: setiap paket kerja memuat hasil yang diharapkan, bukan sekadar daftar kegiatan. Struktur mengikuti lima tahap pada KAK, ditambah kategori manajemen proyek. Aturan 100% dipenuhi karena seluruh paket kerja di bawah setiap kategori mencakup seluruh pekerjaan induknya, dan setiap lingkup KAK memiliki padanan paket kerja. Singkatan PIC: PL (Project Leader), SA (System Analyst), BE (Backend), FE (Frontend), QA (QA & Documentation).

| Kode | Kategori / Paket Kerja | Deliverable | PIC | Minggu |
| --- | --- | --- | --- | --- |
| 0 | **Sistem Informasi Kolaborasi Pendidikan dan Industri** | Sistem siap digunakan dan seluruh dokumen | PL | 1–12 |
| 1 | **Manajemen Proyek** |  | PL | 1–12 |
| 1.1 | Project Charter dan perencanaan | Project Charter, WBS, jadwal, sprint backlog | PL | 1 |
| 1.2 | Pemantauan dan pelaporan progres | Laporan progres mingguan | PL | 1–12 |
| 1.3 | Penutupan proyek | Laporan akhir, berita acara serah terima, lessons learned | PL, QA | 12 |
| 2 | **Analisis Kebutuhan** |  | SA | 1–2 |
| 2.1 | Identifikasi kebutuhan pengguna | Hasil wawancara/kuesioner dan daftar kebutuhan | SA | 1 |
| 2.2 | Analisis alur program yang berjalan | Dokumen alur kondisi saat ini dan daftar masalah | SA | 1–2 |
| 2.3 | Penetapan fitur, data, dan hak akses | PRD/SRS disahkan dan matriks hak akses | SA, PL | 2 |
| 3 | **Perancangan Sistem** |  | SA | 3–5 |
| 3.1 | Arsitektur sistem dan alur kolaborasi | Dokumen arsitektur dan diagram alur kolaborasi | SA, BE | 3 |
| 3.2 | Basis data dan pemodelan | Use case, activity diagram, ERD, kamus data | SA, BE | 3–4 |
| 3.3 | Desain UI/UX | Wireframe, prototipe, dan panduan gaya | FE | 4–5 |
| 4 | **Pengembangan Sistem** |  | BE, FE | 6–9 |
| 4.1 | Fondasi sistem | Repositori, skema basis data, autentikasi, hak akses, tata letak responsif | BE, FE | 6–7 |
| 4.2 | Modul Admin | Data master, pengawasan program, rekap dan laporan | BE, FE | 6–9 |
| 4.3 | Modul Mitra Industri | Profil dan peluang program, seleksi, penilaian | BE, FE | 6–9 |
| 4.4 | Modul Mahasiswa | Pendaftaran, pengajuan penempatan, kegiatan, dokumen, laporan | BE, FE | 6–9 |
| 4.5 | Modul Dosen | Persetujuan, pemantauan, pembimbingan, evaluasi | BE, FE | 8–9 |
| 4.6 | Notifikasi dan komunikasi | Notifikasi dalam sistem dan email | BE | 8–9 |
| 4.7 | Integrasi antarmodul | Alur kolaborasi empat peran berjalan utuh | BE, FE | 9 |
| 5 | **Pengujian Sistem** |  | QA | 10–11 |
| 5.1 | Uji fungsi dan alur antarperan | Skenario dan hasil uji fungsi | QA | 10 |
| 5.2 | Uji perangkat dan peramban | Laporan uji responsif dan kompatibilitas | QA, FE | 10–11 |
| 5.3 | Uji performa dan keamanan | Laporan uji performa dan keamanan | QA, BE | 10–11 |
| 5.4 | UAT dan perbaikan | Berita acara UAT dan daftar perbaikan | QA, PL | 11 |
| 6 | **Implementasi Sistem** |  | PL | 9–12 |
| 6.1 | Deployment hosting dan domain | Sistem berjalan di hosting dengan domain (staging minggu 9–10, produksi minggu 12) | BE | 9–12 |
| 6.2 | Migrasi data awal | Data mahasiswa, dosen, mitra, dan program termigrasi | BE, SA | 12 |
| 6.3 | Pelatihan dan panduan pengguna | Materi pelatihan dan panduan tiap peran | QA, SA | 12 |
| 6.4 | Publikasi dan serah terima | Sistem dipublikasikan dan berita acara serah terima | PL | 12 |

## 4. Deliverable, Milestone, Metodologi, dan Estimasi

### 4.1 Deliverable

Deliverable adalah hasil nyata proyek yang dapat diperiksa dan diserahkan. Daftar berikut menjadi dasar penerimaan hasil kerja oleh sponsor dan dosen pembimbing.

| Kode | Deliverable | Tahap | PIC | Minggu |
| --- | --- | --- | --- | --- |
| D01 | Project Charter, WBS, jadwal, dan sprint backlog | Perencanaan | PL | 1 |
| D02 | PRD/SRS beserta matriks hak akses | Analisis | SA | 2 |
| D03 | Dokumen perancangan: arsitektur, use case, activity diagram, ERD, kamus data | Perancangan | SA, BE | 4 |
| D04 | Prototipe UI/UX | Perancangan | FE | 5 |
| D05 | Increment Sprint 1: autentikasi, hak akses, data master, peluang program, pendaftaran, pengajuan penempatan | Pengembangan | BE, FE | 7 |
| D06 | Increment Sprint 2: sistem terintegrasi seluruh modul | Pengembangan | BE, FE | 9 |
| D07 | Dokumen pengujian: rencana, skenario, hasil uji, dan berita acara UAT | Pengujian | QA | 11 |
| D08 | Sistem yang berjalan di hosting dan domain dengan data awal | Implementasi | BE | 12 |
| D09 | Panduan pengguna tiap peran dan materi pelatihan | Implementasi | QA, SA | 12 |
| D10 | Laporan akhir, presentasi, berita acara serah terima, dan lessons learned | Penutupan | PL | 12 |

### 4.2 Milestone

Milestone adalah tonggak pencapaian yang menandai kemajuan proyek. Bedanya dengan deliverable, milestone berupa peristiwa pengesahan atau penyelesaian, misalnya dokumen PRD selesai dibuat adalah deliverable, sedangkan PRD disahkan adalah milestone.

| Kode | Milestone | Minggu | Bukti pencapaian |
| --- | --- | --- | --- |
| M1 | Project Charter disetujui | 1 | Tanda tangan sponsor dan dosen pembimbing |
| M2 | PRD/SRS disahkan | 2 | Persetujuan tertulis atas D02 |
| M3 | Desain sistem dan prototipe disetujui | 5 | Persetujuan D03 dan D04 setelah evaluasi bersama calon pengguna |
| M4 | Sprint 1 selesai | 7 | Sprint review dan increment D05 |
| M5 | Sprint 2 selesai | 9 | Sprint review dan increment D06 |
| M6 | UAT dinyatakan lulus | 11 | Berita acara UAT |
| M7 | Sistem berhasil di-deploy dan dipublikasikan | 12 | Sistem dapat diakses melalui domain |
| M8 | Serah terima proyek | 12 | Berita acara serah terima |

### 4.3 Pemilihan Metodologi

Proyek ini menggunakan pendekatan hibrida: kerangka fase berurutan ala Waterfall untuk keseluruhan 12 minggu, dengan tahap pengembangan dijalankan sebagai dua sprint Scrum. Pilihan ini diambil karena dua hal yang berbeda sifatnya. Pertama, lingkup, hak akses, dan keluaran sudah ditetapkan dalam KAK, jadwal berupa lima tahap berurutan dengan durasi tetap, dan dokumentasi analisis, perancangan, serta pengujian wajib diserahkan. Kondisi ini cocok dengan Waterfall yang menuntut perencanaan dan dokumentasi rinci sejak awal. Kedua, tahap pengembangan mencakup empat modul dengan pengguna yang beragam sehingga umpan balik berkala dari mahasiswa, dosen, dan mitra industri sangat membantu. Dua sprint dua mingguan dengan sprint review memberi kesempatan itu tanpa mengubah kerangka jadwal.

| Aspek | Penerapan pada proyek ini |
| --- | --- |
| Alur kerja | Analisis, perancangan, pengembangan, pengujian, dan implementasi berurutan; pengembangan dibagi Sprint 1 (minggu 6–7) dan Sprint 2 (minggu 8–9) |
| Perubahan kebutuhan | Setelah PRD disahkan, perubahan melalui permintaan perubahan yang disetujui Project Leader dan dosen pembimbing; urutan prioritas item dalam sprint backlog boleh disesuaikan saat sprint planning |
| Perencanaan | WBS dan Gantt Chart per fase, serta sprint backlog dan sprint goal untuk tahap pengembangan |
| Umpan balik pengguna | Evaluasi desain pada minggu 5, sprint review pada minggu 7 dan 9, serta UAT pada minggu 11 |
| Peran Scrum | Product Owner: System Analyst; Scrum Master: Project Leader; tim pengembang: Backend, Frontend, dan QA |
| Kegiatan sprint | Sprint planning di awal sprint, daily scrum singkat 15 menit, sprint review dengan perwakilan pengguna, dan retrospektif di akhir sprint |

### 4.4 Estimasi

Estimasi memadukan tiga teknik. Top-down dipakai pada tahap awal: total 12 minggu dari KAK dibagi menjadi 2 minggu analisis, 3 minggu perancangan, 4 minggu pengembangan, 2 minggu pengujian, dan 1 minggu implementasi. Bottom-up dipakai untuk menguji kewajaran angka tersebut dengan menjumlahkan estimasi jam per paket kerja WBS. Parametric dipakai untuk pengujian fungsi, yaitu enam modul (autentikasi dan hak akses, Admin, Mitra, Mahasiswa, Dosen, notifikasi) dikalikan rata-rata lima jam per modul sehingga diperoleh 30 jam. Teknik analogous tidak dijadikan dasar karena kelompok belum memiliki proyek serupa sebagai pembanding.

Asumsi kapasitas adalah lima anggota dengan ketersediaan efektif 16 jam per minggu per orang, sehingga kapasitas tim 80 jam per minggu atau 960 jam selama 12 minggu.

| Tahap | Paket WBS | Estimasi (jam) | Kapasitas tahap (jam) | Keterangan |
| --- | --- | --- | --- | --- |
| Manajemen proyek | 1.1–1.3 | 50 | tersebar | Dikerjakan Project Leader sepanjang proyek |
| Analisis | 2.1–2.3 | 80 | 160 | Longgar, waktu sisa untuk wawancara tambahan |
| Perancangan | 3.1–3.3 | 140 | 240 | Wireframe dapat dimulai paralel dengan ERD |
| Pengembangan | 4.1–4.7 | 290 | 320 | Paling padat, margin sekitar 10% |
| Pengujian | 5.1–5.4 | 100 | 160 | Termasuk perbaikan temuan UAT |
| Implementasi | 6.1–6.4 | 70 | 80 | Staging disiapkan lebih awal pada minggu 9–10 |
| **Total** |  | **730** | **960** | Cadangan sekitar 24% |

Hasil bottom-up (730 jam) masih berada di bawah kapasitas (960 jam), sehingga jadwal 12 minggu dinilai realistis. Titik paling rawan adalah tahap pengembangan, karena itu fitur berprioritas Could dipindahkan lebih dulu bila kapasitas sprint tidak cukup.

## 5. Jadwal Proyek dan Gantt Chart

Jadwal mengikuti lima tahap pada KAK dengan total 12 minggu. Tanda ■ menunjukkan minggu pelaksanaan. Tanggal kalender diisi setelah tanggal kick-off ditetapkan, yaitu Minggu 1 dimulai pada \[tanggal kick-off\].

| Kegiatan | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | PIC |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Pemantauan dan pelaporan progres | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | ■ | PL |
| Project Charter, WBS, jadwal | ■ |  |  |  |  |  |  |  |  |  |  |  | PL |
| Identifikasi kebutuhan pengguna | ■ |  |  |  |  |  |  |  |  |  |  |  | SA |
| Analisis alur program berjalan | ■ | ■ |  |  |  |  |  |  |  |  |  |  | SA |
| Penetapan fitur, data, hak akses (PRD) |  | ■ |  |  |  |  |  |  |  |  |  |  | SA, PL |
| Arsitektur dan alur kolaborasi |  |  | ■ |  |  |  |  |  |  |  |  |  | SA, BE |
| Basis data dan pemodelan |  |  | ■ | ■ |  |  |  |  |  |  |  |  | SA, BE |
| Desain UI/UX dan evaluasi desain |  |  |  | ■ | ■ |  |  |  |  |  |  |  | FE |
| Sprint 1: fondasi, data master, pendaftaran, pengajuan |  |  |  |  |  | ■ | ■ |  |  |  |  |  | BE, FE |
| Sprint 2: dosen, mitra, kegiatan, notifikasi, rekap |  |  |  |  |  |  |  | ■ | ■ |  |  |  | BE, FE |
| Persiapan hosting dan staging |  |  |  |  |  |  |  |  | ■ | ■ |  |  | BE |
| Uji fungsi dan alur antarperan |  |  |  |  |  |  |  |  |  | ■ |  |  | QA |
| Uji perangkat, performa, keamanan |  |  |  |  |  |  |  |  |  | ■ | ■ |  | QA, FE, BE |
| UAT dan perbaikan |  |  |  |  |  |  |  |  |  |  | ■ |  | QA, PL |
| Deployment produksi dan migrasi data |  |  |  |  |  |  |  |  |  |  |  | ■ | BE, SA |
| Pelatihan dan panduan pengguna |  |  |  |  |  |  |  |  |  |  |  | ■ | QA, SA |
| Publikasi, laporan akhir, serah terima |  |  |  |  |  |  |  |  |  |  |  | ■ | PL |

Urutan utama (jalur kritis) adalah analisis, PRD disahkan, desain disetujui, Sprint 1, Sprint 2, pengujian, UAT, lalu deployment. Keterlambatan pada salah satu titik tersebut langsung menggeser tanggal selesai, sehingga pengesahan PRD pada minggu 2 dan persetujuan desain pada minggu 5 dijaga ketat. Agar minggu 12 tidak terlalu padat, hosting dan staging disiapkan sejak minggu 9, templat impor data awal dibuat selama pengembangan, dan skenario UAT disusun sebelum minggu 11.

## 6. Sprint Backlog

Sprint backlog diturunkan dari kebutuhan fungsional pada PRD (Bagian 7). Ukuran item dinyatakan dalam story point (SP) dengan perkiraan satu SP setara sekitar tiga jam kerja. Kapasitas tiap sprint sekitar 160 jam atau kurang lebih 50 SP. Seluruh item berstatus Belum mulai pada saat dokumen ini disusun.

### 6.1 Sprint 1 (Minggu 6–7)

**Sprint goal:** pengguna dapat masuk sesuai perannya, admin dapat mengelola data master, mitra dapat mempublikasikan peluang program, dan mahasiswa dapat mendaftar program serta mengajukan penempatan.

| ID | Item pekerjaan | Ref PRD | PIC | SP | Status |
| --- | --- | --- | --- | --- | --- |
| S1-01 | Penyiapan repositori, lingkungan pengembangan, dan struktur proyek | NFR-07 | BE | 3 | Belum mulai |
| S1-02 | Skema basis data dan migrasi awal | Semua FR | BE | 5 | Belum mulai |
| S1-03 | Autentikasi: masuk, keluar, dan atur ulang kata sandi | FR-01 | BE | 5 | Belum mulai |
| S1-04 | Hak akses berdasarkan empat peran | FR-02 | BE | 5 | Belum mulai |
| S1-05 | Tata letak responsif dan komponen antarmuka dasar | NFR-03 | FE | 5 | Belum mulai |
| S1-06 | Admin: pengelolaan pengguna dan data master | FR-14 | BE, FE | 8 | Belum mulai |
| S1-07 | Mitra: profil dan publikasi peluang program | FR-11 | BE, FE | 5 | Belum mulai |
| S1-08 | Mahasiswa: melihat peluang dan mendaftar program | FR-03 | BE, FE | 5 | Belum mulai |
| S1-09 | Mahasiswa: pengajuan penempatan | FR-04 | BE, FE | 5 | Belum mulai |
| S1-10 | Uji fungsi Sprint 1 dan dokumentasi | Semua item sprint | QA | 3 | Belum mulai |
|  | **Total Sprint 1** |  |  | **49** |  |

### 6.2 Sprint 2 (Minggu 8–9)

**Sprint goal:** seluruh alur kolaborasi empat peran berjalan utuh, mulai dari persetujuan dan seleksi, pencatatan kegiatan, pembimbingan, penilaian, hingga rekapitulasi dan notifikasi.

| ID | Item pekerjaan | Ref PRD | PIC | SP | Status |
| --- | --- | --- | --- | --- | --- |
| S2-01 | Dosen: persetujuan pengajuan penempatan | FR-08 | BE, FE | 5 | Belum mulai |
| S2-02 | Mitra: seleksi dan verifikasi peserta | FR-12 | BE, FE | 5 | Belum mulai |
| S2-03 | Mahasiswa: pencatatan kegiatan | FR-05 | BE, FE | 5 | Belum mulai |
| S2-04 | Mahasiswa: pengelolaan dokumen dan pengumpulan laporan | FR-06, FR-07 | BE, FE | 5 | Belum mulai |
| S2-05 | Dosen: pemantauan, pembimbingan, dan evaluasi | FR-09, FR-10 | BE, FE | 8 | Belum mulai |
| S2-06 | Mitra: penilaian mahasiswa | FR-13 | BE, FE | 5 | Belum mulai |
| S2-07 | Notifikasi dalam sistem dan email | FR-17 | BE | 5 | Belum mulai |
| S2-08 | Admin: pengawasan program, rekapitulasi, dan ekspor laporan | FR-15, FR-16 | BE, FE | 8 | Belum mulai |
| S2-09 | Integrasi antarmodul dan perbaikan temuan Sprint 1 | Semua FR | BE, FE | 3 | Belum mulai |
| S2-10 | Uji fungsi Sprint 2 dan dokumentasi | Semua item sprint | QA | 3 | Belum mulai |
|  | **Total Sprint 2** |  |  | **52** |  |

Bila kapasitas Sprint 2 tidak cukup, item S2-08 bagian ekspor laporan menjadi yang pertama dipindahkan ke akhir minggu 9 atau ke daftar perbaikan setelah UAT, karena berprioritas Should.

### 6.3 Definition of Done

Sebuah item dinyatakan selesai apabila kodenya telah ditinjau dan digabungkan ke cabang utama, memenuhi kriteria penerimaan pada PRD, lulus uji fungsi oleh QA, tampil baik pada layar ponsel dan desktop, serta tercatat dalam dokumentasi sprint. Hasil tiap sprint diperlihatkan kepada perwakilan pengguna pada sprint review, dan masukan yang muncul dimasukkan ke backlog untuk diprioritaskan pada sprint berikutnya atau daftar perbaikan UAT.

## 7. Product Requirements Document (PRD)

PRD ini menjabarkan kebutuhan produk secara rinci sebagai dasar perancangan, pengembangan, dan pengujian. Dokumen ini menjadi deliverable D02 dan disahkan pada milestone M2 (minggu 2).

### 7.1 Ringkasan Produk

Produk yang dibangun adalah sistem informasi berbasis web untuk mengelola kolaborasi pendidikan dan industri, dengan nama kerja \[Nama Produk\]. Sistem menghubungkan mahasiswa, dosen, mitra industri, dan admin dalam satu alur yang terpusat, mulai dari pendaftaran program, pengajuan penempatan, seleksi, pelaksanaan dan pembimbingan, penilaian, hingga rekapitulasi. Masalah yang diselesaikan adalah proses yang manual dan tersebar, persetujuan yang lambat, data peserta dan mitra yang tidak terintegrasi, serta pemantauan capaian yang kurang akurat.

### 7.2 Sasaran dan Indikator Keberhasilan

Nilai awal (baseline) diukur pada tahap analisis kebutuhan. Target di bawah ini bersifat usulan dan dapat dikoreksi saat PRD disahkan.

| Sasaran | Indikator | Target |
| --- | --- | --- |
| Seluruh proses terpusat | Persentase program mahasiswa yang tercatat di sistem pada data awal | 100% program yang sedang berjalan |
| Persetujuan lebih cepat | Waktu dosen merespons pengajuan penempatan | Lebih cepat dari proses manual, ditargetkan paling lama 3 hari kerja |
| Pemantauan akurat | Kegiatan dan laporan mahasiswa tercatat dan dapat dilihat dosen dan mitra | Seluruh peserta aktif memiliki catatan kegiatan di sistem |
| Pelaporan efisien | Rekap per program, program studi, dan mitra tersedia dari sistem | Tersedia tanpa penyusunan manual |
| Sistem diterima pengguna | Hasil UAT | 100% skenario Must lulus dan kepuasan pengguna UAT paling sedikit 80% |

### 7.3 Pengguna dan Kebutuhan Utamanya

| Peran | Kebutuhan utama | Hasil yang diharapkan |
| --- | --- | --- |
| Mahasiswa | Menemukan program, mendaftar, mengajukan penempatan, mencatat kegiatan, dan mengumpulkan dokumen dan laporan | Proses jelas, status terlihat, tanpa berkas tercecer |
| Dosen | Menyetujui pengajuan, memantau perkembangan, membimbing, dan mengevaluasi | Pemantauan berbasis data dan bimbingan terdokumentasi |
| Mitra industri | Mempublikasikan peluang, menyeleksi dan memverifikasi peserta, serta menilai mahasiswa | Mendapat peserta yang sesuai dan penilaian yang terstruktur |
| Admin | Mengelola pengguna dan data master, mengawasi seluruh program, dan menyusun rekap | Data terpusat dan laporan cepat dibuat |

### 7.4 Ruang Lingkup Produk

Lingkup produk mengikuti KAK dan Project Charter: empat modul peran beserta autentikasi, hak akses, dan notifikasi, berbasis web dan responsif. Hal yang tidak termasuk adalah aplikasi mobile native, integrasi dengan sistem akademik atau PDDikti, pembayaran, dan penerbitan sertifikat elektronik. Hal-hal tersebut dicatat sebagai pengembangan lanjutan.

### 7.5 Alur Bisnis Utama

Alur dimulai ketika mitra industri mempublikasikan peluang program. Mahasiswa melihat peluang tersebut, mendaftar, lalu mengajukan penempatan. Dosen memeriksa pengajuan dan menyetujui, menolak, atau meminta revisi. Pengajuan yang disetujui diteruskan kepada mitra untuk seleksi dan verifikasi. Setelah peserta diterima, program berjalan: mahasiswa mencatat kegiatan, mengelola dokumen, dan mengumpulkan laporan, sementara dosen memantau dan membimbing. Di akhir program mitra memberi penilaian, dosen melakukan evaluasi, dan admin merekap seluruh data untuk pelaporan. Notifikasi dikirim setiap status penting berubah.

| Status pengajuan | Makna | Pihak yang mengubah |
| --- | --- | --- |
| Draf | Belum diajukan, masih dapat diubah | Mahasiswa |
| Diajukan | Menunggu keputusan dosen | Mahasiswa |
| Perlu revisi | Dikembalikan dosen dengan catatan | Dosen |
| Disetujui dosen | Menunggu seleksi mitra | Dosen |
| Ditolak | Tidak dapat dilanjutkan, disertai alasan | Dosen atau mitra |
| Diterima mitra | Peserta terverifikasi | Mitra |
| Berjalan | Pelaksanaan program berlangsung | Sistem, sesuai periode |
| Selesai | Penilaian dan evaluasi telah terisi | Sistem, setelah penilaian lengkap |

### 7.6 Kebutuhan Fungsional

Prioritas memakai MoSCoW: Must wajib ada pada rilis, Should penting namun dapat bergeser bila kapasitas tidak cukup, Could bersifat tambahan.

| ID | Modul | Kebutuhan | Prioritas | Kriteria penerimaan |
| --- | --- | --- | --- | --- |
| FR-01 | Umum | Masuk, keluar, dan atur ulang kata sandi | Must | Pengguna terdaftar dapat masuk dengan email dan kata sandi; tautan atur ulang kata sandi berlaku terbatas; kata sandi tersimpan dalam bentuk terenkripsi |
| FR-02 | Umum | Hak akses berdasarkan peran | Must | Setiap halaman dan aksi hanya dapat diakses peran yang berwenang; akses tanpa hak ditolak |
| FR-03 | Mahasiswa | Melihat peluang dan mendaftar program | Must | Mahasiswa dapat mencari dan menyaring peluang, melihat detail, dan mendaftar; status pendaftaran tampil |
| FR-04 | Mahasiswa | Pengajuan penempatan | Must | Mahasiswa memilih program atau mitra, mengisi formulir, melampirkan berkas, dan mengajukan; status mengikuti alur pada 7.5 |
| FR-05 | Mahasiswa | Pencatatan kegiatan | Must | Catatan berisi tanggal, uraian, dan bukti; dapat diubah sebelum ditinjau; dapat dilihat dosen dan mitra terkait |
| FR-06 | Mahasiswa | Pengelolaan dokumen | Must | Dokumen dapat diunggah, dilihat, diganti, dan dihapus dengan batasan format dan ukuran berkas |
| FR-07 | Mahasiswa | Pengumpulan laporan | Must | Laporan diunggah sesuai tenggat, keterlambatan tertandai, dan dosen dapat meninjau |
| FR-08 | Dosen | Persetujuan pengajuan | Must | Dosen dapat menyetujui, menolak, atau meminta revisi disertai alasan; mahasiswa menerima pemberitahuan |
| FR-09 | Dosen | Pemantauan dan pembimbingan | Must | Dosen melihat daftar mahasiswa bimbingan beserta progres dan catatan kegiatan, serta memberi catatan bimbingan |
| FR-10 | Dosen | Evaluasi mahasiswa | Must | Dosen mengisi penilaian sesuai komponen; nilai tersimpan dan dapat dilihat admin |
| FR-11 | Mitra | Profil mitra dan peluang program | Must | Mitra mengelola profil serta membuat, mengubah, dan menutup peluang (kuota, syarat, periode); peluang tampil bagi mahasiswa setelah dipublikasikan |
| FR-12 | Mitra | Seleksi dan verifikasi peserta | Must | Mitra melihat pelamar, menerima atau menolak, dan memverifikasi peserta; kuota tidak dapat terlampaui |
| FR-13 | Mitra | Penilaian mahasiswa | Must | Mitra mengisi penilaian berdasar rubrik hanya untuk peserta yang berstatus berjalan atau selesai |
| FR-14 | Admin | Pengelolaan pengguna dan data master | Must | Admin menambah, mengubah, dan menonaktifkan pengguna serta mengelola data master; data awal dapat diimpor dari berkas CSV |
| FR-15 | Admin | Pengawasan seluruh program | Must | Admin melihat seluruh program, pengajuan, dan penempatan beserta statusnya dengan fitur penyaringan |
| FR-16 | Admin | Rekapitulasi dan laporan | Should | Rekap per program, program studi, dan mitra tampil di layar; ekspor ke Excel atau PDF tersedia |
| FR-17 | Umum | Notifikasi dan komunikasi | Must | Notifikasi dalam sistem muncul pada perubahan status penting dan komentar antar pihak; notifikasi email bersifat tambahan (Could) |

### 7.7 Matriks Hak Akses

Tanda ✓ berarti dapat melakukan (membuat, mengubah, atau memutuskan), “lihat” berarti hanya dapat membaca, dan tanda kosong berarti tidak memiliki akses.

| Fitur | Mahasiswa | Dosen | Mitra | Admin |
| --- | --- | --- | --- | --- |
| Masuk dan kelola akun sendiri | ✓ | ✓ | ✓ | ✓ |
| Peluang program | lihat | lihat | ✓ | lihat |
| Pendaftaran dan pengajuan penempatan | ✓ | lihat | lihat | lihat |
| Persetujuan pengajuan |  | ✓ |  | lihat |
| Seleksi dan verifikasi peserta |  | lihat | ✓ | lihat |
| Catatan kegiatan, dokumen, laporan | ✓ | lihat | lihat | lihat |
| Bimbingan dan evaluasi | lihat | ✓ |  | lihat |
| Penilaian mitra | lihat | lihat | ✓ | lihat |
| Data master dan pengguna |  |  |  | ✓ |
| Rekap dan laporan |  | lihat | lihat | ✓ |

### 7.8 Kebutuhan Nonfungsional

| ID | Aspek | Kebutuhan |
| --- | --- | --- |
| NFR-01 | Keamanan | Kata sandi di-hash, seluruh masukan divalidasi, sesi dilindungi, dan akses lewat HTTPS |
| NFR-02 | Performa | Halaman utama termuat paling lama sekitar 3 detik pada koneksi normal dengan sekitar 100 pengguna bersamaan |
| NFR-03 | Responsif dan kompatibilitas | Tampilan baik pada layar ponsel, tablet, dan desktop serta pada Chrome, Firefox, Edge, dan Safari versi terbaru |
| NFR-04 | Kegunaan | Tugas utama tiap peran dapat diselesaikan dengan panduan singkat; navigasi konsisten |
| NFR-05 | Ketersediaan dan pencadangan | Pencadangan basis data berkala, minimal harian, dan pemulihan dapat diuji |
| NFR-06 | Jejak audit | Aktivitas penting (persetujuan, perubahan status, perubahan data master) tercatat beserta pelaku dan waktunya |
| NFR-07 | Keterpeliharaan | Kode tersimpan di repositori terkontrol, terstruktur, dan didokumentasikan |
| NFR-08 | Berkas unggahan | Format dan ukuran dibatasi (usulan: PDF, JPG, PNG, DOCX, maksimal 5 MB per berkas) dan hanya dapat diakses pihak berwenang |

### 7.9 Data Utama

Entitas berikut menjadi dasar perancangan ERD pada tahap perancangan dan dapat disempurnakan.

| Entitas | Keterangan singkat |
| --- | --- |
| Pengguna dan Peran | Akun, peran (mahasiswa, dosen, mitra, admin), dan status aktif |
| Mahasiswa, Dosen, Mitra | Profil masing-masing beserta keterkaitannya dengan pengguna |
| Program | Jenis program, periode, kuota, syarat, mitra penyelenggara, dan status publikasi |
| Pendaftaran dan Penempatan | Hubungan mahasiswa dengan program, status, dosen pembimbing, dan mitra |
| Kegiatan, Dokumen, Laporan | Catatan pelaksanaan, berkas, dan laporan beserta tenggat |
| Bimbingan dan Penilaian | Catatan bimbingan dosen, nilai dosen, dan nilai mitra |
| Notifikasi dan Log Aktivitas | Pemberitahuan sistem dan jejak audit |
|  |  |

### 7.10 Usulan Teknologi

Usulan awal adalah aplikasi web berbasis framework PHP Laravel dengan basis data MySQL, antarmuka responsif, dan hosting yang mendukung HTTPS serta pencadangan. Keputusan akhir ditetapkan pada paket kerja 3.1 (arsitektur sistem) sesuai kemampuan tim dan ketersediaan hosting.

### 7.11 Asumsi, Dependensi, dan Risiko

Asumsi dan risiko mengacu pada Project Charter (Bagian 2.8) dan KAK (Bagian 1.8). Dependensi utama adalah ketersediaan perwakilan pengguna untuk wawancara, evaluasi desain, sprint review, dan UAT, ketersediaan data awal untuk migrasi, serta kesiapan hosting dan domain sebelum minggu 9.

### 7.12 Keterlacakan Kebutuhan

Tabel berikut menunjukkan bahwa setiap lingkup KAK terpenuhi oleh paket kerja WBS, kebutuhan PRD, dan item sprint.

| Lingkup KAK | Paket WBS | Kebutuhan PRD | Item sprint |
| --- | --- | --- | --- |
| Analisis kebutuhan | 2.1–2.3 | Seluruh PRD | Di luar sprint |
| Perancangan sistem | 3.1–3.3 | 7.9 dan 7.10 | Di luar sprint |
| Modul Mahasiswa | 4.4 | FR-03 sampai FR-07 | S1-08, S1-09, S2-03, S2-04 |
| Modul Dosen | 4.5 | FR-08 sampai FR-10 | S2-01, S2-05 |
| Modul Mitra Industri | 4.3 | FR-11 sampai FR-13 | S1-07, S2-02, S2-06 |
| Modul Admin | 4.2 | FR-14 sampai FR-16 | S1-06, S2-08 |
| Autentikasi, hak akses, notifikasi | 4.1, 4.6 | FR-01, FR-02, FR-17 | S1-03, S1-04, S2-07 |
| Pengujian sistem | 5.1–5.4 | Semua FR dan NFR | S1-10, S2-10 |
| Implementasi sistem | 6.1–6.4 | NFR-05, FR-14 (impor data) | Di luar sprint |
