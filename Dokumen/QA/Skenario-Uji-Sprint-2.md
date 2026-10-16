# Skenario Uji Fungsi — Sprint 2

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Sprint:** Sprint 2 (Minggu 8–9)
**Build uji:** cabang `docs/qa-sprint-2` (integrasi `main` = S1-01…S1-10 + S2-01…S2-09)
**Tanggal:** 2026-10-16

---

## Cara Pakai

- **Hasil aktual** diisi saat eksekusi. Nilai **Status**: `Lulus`, `Gagal`, `Belum diuji`.
- Kolom **Otomatis** menandai skenario yang sudah dicakup uji otomatis (`php artisan test`); lihat `Laporan-Uji-Sprint-2.md` untuk bukti.
- Skenario bertanda **Manual** wajib dijalankan di browser/HP asli oleh QA.
- **Referensi**: FR/NFR mengacu ke `Dokumen Perencanaan` (KAK/PRD), status pendaftaran §7.5, matriks hak akses §7.7.

**Akun uji** (dari `DatabaseSeeder`, kata sandi awal `password`):

| Peran | Email |
|-------|-------|
| Admin | `admin@skp.test` |
| Dosen | `dosen@skp.test` |
| Mitra | `mitra@skp.test` |
| Mahasiswa | `mahasiswa@skp.test` |

---

## A. Autentikasi & Akun (regresi S1, FR-01)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-AUTH-01 | Masuk admin | Login `admin@skp.test` | Diarahkan ke dashboard admin | | ✔ `AuthenticationTest` | Lulus |
| TC-AUTH-02 | Masuk dosen | Login `dosen@skp.test` | Diarahkan ke area dosen (pengajuan) | | ✔ | Lulus |
| TC-AUTH-03 | Masuk mitra | Login `mitra@skp.test` | Diarahkan ke area mitra (peluang) | | ✔ | Lulus |
| TC-AUTH-04 | Masuk mahasiswa | Login `mahasiswa@skp.test` | Diarahkan ke katalog peluang | | ✔ | Lulus |
| TC-AUTH-05 | Kredensial salah | Login email benar + sandi salah | Tetap di login, pesan galat generik | | ✔ | Lulus |
| TC-AUTH-06 | Akun nonaktif | Login akun `is_active=false` | Ditolak, tidak dapat masuk | | ✔ | Lulus |
| TC-AUTH-07 | Keluar | Klik Keluar | Sesi berakhir, diarahkan ke login | | ✔ | Lulus |

## B. Hak Akses Empat Peran (regresi S1, FR-02, matriks §7.7)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-RBAC-01 | Redirect per peran | Login tiap peran | Mendarat di area perannya masing-masing | | ✔ `RoleAccessTest` | Lulus |
| TC-RBAC-02 | Mahasiswa → area admin | Akses `/admin/users` sbg mahasiswa | `403 Forbidden` | | ✔ `AccessMatrixTest` | Lulus |
| TC-RBAC-03 | Dosen → area mitra | Akses `/mitra/program` sbg dosen | `403` | | ✔ | Lulus |
| TC-RBAC-04 | Mitra → area mahasiswa | Akses `/mahasiswa/pendaftaran` sbg mitra | `403` | | ✔ | Lulus |
| TC-RBAC-05 | Akses tanpa login | Buka URL area privat tanpa sesi | Dialihkan ke halaman login | | ✔ | Lulus |
| TC-RBAC-06 | Data milik orang lain | Mahasiswa A buka pendaftaran mahasiswa B | `403` (policy kepemilikan) | | ✔ | Lulus |
| TC-RBAC-07 | Admin tidak hapus akun sendiri | Admin nonaktifkan/hapus akun sendiri | Ditolak | | ✔ `UserManagementTest` | Lulus |
| TC-RBAC-08 | Hapus pengguna berelasi | Hapus pengguna yang punya pendaftaran/program | Hanya boleh dinonaktifkan, tidak dihapus | | ✔ | Lulus |

## C. Admin — Pengguna & Data Master (regresi S1, FR-14)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-ADM-01 | Daftar pengguna | Buka `/admin/users` | Tabel pengguna; pencarian + filter peran & status | | ✔ `UserManagementTest` | Lulus |
| TC-ADM-02 | Tambah pengguna | Isi form + pilih peran | Pengguna dibuat; profil otomatis terbuat | | ✔ | Lulus |
| TC-ADM-03 | Impor CSV valid | Unggah CSV header `nama,email,role` | Satu transaksi; ringkasan berhasil | | ✔ `UserImportTest` | Lulus |
| TC-ADM-04 | Impor CSV header salah | Unggah CSV tanpa kolom wajib | Ditolak, error pada `file` | | ✔ | Lulus |
| TC-ADM-05 | Data master | CRUD program studi & periode akademik | Tersimpan; aturan unik/aktif ditegakkan | | ✔ `MasterDataTest` | Lulus |
| TC-ADM-06 | Rekapitulasi | Buka `/admin/rekap` | Tabel rekap per program/prodi/mitra, ekspor CSV/Excel | | ✔ `RekapTest` | Lulus |

## D. Dosen — Persetujuan Pengajuan (S2-01, FR-11)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-DOS-01 | Antrean pengajuan | Dosen buka `/dosen/pengajuan` | Hanya pengajuan ditujukan kepadanya, urut tertua | | ✔ `PersetujuanPengajuanTest` | Lulus |
| TC-DOS-02 | Setujui pengajuan | Dosen tekan Setujui | Status → `disetujui_dosen`; notifikasi mahasiswa; audit log | | ✔ | Lulus |
| TC-DOS-03 | Tolak pengajuan | Dosen tekan Tolak + alasan | Status → `ditolak`; alasan disimpan di `catatan` | | ✔ | Lulus |
| TC-DOS-04 | Revisi pengajuan | Dosen tekan Revisi + catatan | Status → `perlu_revisi`; mahasiswa dapat revisi | | ✔ | Lulus |
| TC-DOS-05 | Aksi idempoten | Dosen ulangi keputusan yang sama | Tidak diproses ulang | | ✔ | Lulus |
| TC-DOS-06 | Bukan pembimbing | Dosen lain buka pengajuan bukan miliknya | `403` | | ✔ | Lulus |
| TC-DOS-07 | Penugasan otomatis | Mahasiswa ajukan penempatan | Sistem tugas dosen pembimbing (prodi sama, beban terendah) | | ✔ | Lulus |
| TC-DOS-08 | Detail pengajuan | Dosen buka detail pengajuan | Identitas mahasiswa/prodi, mitra, program, status, berkas | | ✔ | Lulus |

## E. Mitra — Seleksi Peserta (S2-02, FR-12)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-MTR-01 | Antrean pelamar | Mitra buka `/mitra/program/{id}/pelamar` | Hanya `disetujui_dosen` untuk programnya, urut tertua + sisa kuota | | ✔ `SeleksiMitraTest` | Lulus |
| TC-MTR-02 | Terima peserta | Mitra tekan Terima | Status → `diterima_mitra`; kuota terkurangi; notifikasi mahasiswa | | ✔ | Lulus |
| TC-MTR-03 | Kuota penuh | Terima melebihi kuota program | Ditolak, pesan "Kuota program sudah penuh" | | ✔ | Lulus |
| TC-MTR-04 | Terima paralel (race) | 2 req terima bersamaan | Hanya 1 berhasil, yang lain ditolak kuota | | ✔ | Lulus |
| TC-MTR-05 | Tolak peserta | Mitra tekan Tolak + alasan wajib | Status → `ditolak`; alasan di `catatan`; tanpa alasan ditolak | | ✔ | Lulus |
| TC-MTR-06 | Bukan penyelenggara | Mitra lain akses pelamar program ini | `403` | | ✔ | Lulus |
| TC-MTR-07 | Status bukan menunggu | Coba terima/tolak non-`disetujui_dosen` | `403` | | ✔ | Lulus |
| TC-MTR-08 | Program mulai berjalan | `tanggal_mulai` tiba | `diterima_mitra` → `berjalan` (via command harian) | | ✔ | Lulus |

## F. Mahasiswa — Pencatatan Kegiatan (S2-03, FR-05)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-MHS-01 | Buat kegiatan | Mahasiswa isi tanggal + uraian + bukti | Kegiatan tersimpan miliknya, status menunggu ditinjau | | ✔ `KegiatanTest` | Lulus |
| TC-MHS-02 | Ubah sebelum ditinjau | Mahasiswa ubah kegiatan sebelum catatan dosen | Berhasil diubah | | ✔ | Lulus |
| TC-MHS-03 | Terkunci setelah bimbingan | Coba ubah setelah dosen catat bimbingan | Ditolak (terkunci) | | ✔ | Lulus |
| TC-MHS-04 | Daftar kegiatan sendiri | Buka daftar kegiatan miliknya | Tampil semua kegiatan + status | | ✔ | Lulus |

## G. Mahasiswa — Dokumen & Laporan (S2-04, NFR-08)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-DOK-01 | Unggah dokumen | Mahasiswa unggah berkas laporan | Tersimpan di `storage/app/private` (privat) | | ✔ `DokumenLaporanTest` | Lulus |
| TC-DOK-02 | Validasi berkas | Unggah > 5 MB / format salah | Ditolak | | ✔ | Lulus |
| TC-DOK-03 | Unduh dokumen | Mahasiswa unduh dokumen miliknya | Berkas terunduh | | ✔ | Lulus |
| TC-DOK-04 | Akses berkas orang lain | Akses URL berkas tanpa hak | `403`/`404` | | ✔ | Lulus |
| TC-DOK-05 | Kumpulkan laporan | Mahasiswa kumpulkan laporan akhir | Status laporan berubah | | ✔ | Lulus |
| TC-DOK-06 | Laporan terlambat | Kumpulkan setelah tenggat | Ditandai TERLAMBAT | | ✔ | Lulus |

## H. Dosen — Bimbingan & Penilaian (S2-05, S2-06)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-BIM-01 | Daftar bimbingan | Dosen buka daftar bimbingan | Tampil mahasiswa yang dibimbingnya | | ✔ `BimbinganPenilaianTest` | Lulus |
| TC-BIM-02 | Catat bimbingan | Dosen catat bimbingan pada kegiatan | Catatan tersimpan; kegiatan terkunci | | ✔ | Lulus |
| TC-BIM-03 | Penilaian dosen | Dosen isi form penilaian | Nilai tersimpan; dikaitkan ke pendaftaran | | ✔ | Lulus |
| TC-BIM-04 | Penilaian milik orang lain | Dosen lain buka penilaian | `403` | | ✔ | Lulus |

## I. Mitra — Penilaian Mahasiswa (S2-06)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TCPEN-01 | Daftar penilaian | Mitra buka daftar penilaian | Tampil mahasiswa yang diterimanya | | ✔ `PenilaianMitraTest` | Lulus |
| TCPEN-02 | Isi penilaian | Mitra isi form penilaian mahasiswa | Nilai tersimpan | | ✔ | Lulus |
| TCPEN-03 | Kepemilikan | Mitra lain nilai mahasiswa bukan miliknya | `403` | | ✔ | Lulus |

## J. Notifikasi Sistem & Email (S2-07)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-NOT-01 | Notifikasi in-app | Setiap status berubah pada pendaftaran | Notifikasi dibuat untuk mahasiswa | | ✔ `NotifikasiKomentarTest` | Lulus |
| TC-NOT-02 | Badge belum dibaca | Navbar menampilkan count notifikasi belum dibaca | Badge angka tampil di ikon lonceng | | ✔ | Lulus |
| TC-NOT-03 | Komentar notifikasi | Komentar baru pada pendaftaran | Notifikasi terkait terpicu | | ✔ | Lulus |
| TC-NOT-04 | Email status penting | Perubahan status utama | Email terkirim (via listener, fail-safe log) | | ✔ | Lulus |

## K. Integrasi Antarmodul (S2-09)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-INT-01 | Alur akhir ke akhir | Mahasiswa daftar → dosen setujui → mitra terima → berjalan → laporan | Status transisi mulus di semua modul | | ✔ (seluruh suite) | Lulus |
| TC-INT-02 | Audit lintas modul | Periksa `activity_logs` | Setiap aksi status-changing tercatat dengan aksi yang benar | | ✔ | Lulus |

## L. Non-Fungsional (NFR)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-NFR-01 | Responsif HP (360px) | Buka tiap halaman utama di 360px | Tata letak rapi, tanpa scroll horizontal | | Manual | Belum diuji |
| TC-NFR-02 | Responsif desktop (≥1366px) | Buka di desktop | Tata letak rapi & konsisten | | Manual | Belum diuji |
| TC-NFR-03 | Lintas browser | Chrome + 1 browser lain | Fungsi utama berjalan | | Manual | Belum diuji |
| TC-NFR-04 | Jejak audit | Periksa `activity_logs` | Aksi penting tercatat (NFR-06) | | ✔ | Lulus |
| TC-NFR-05 | Berkas privat | Akses berkas via URL tanpa hak | `403`/`404`; tidak ter-serve web (NFR-08) | | ✔ | Lulus |
| TC-NFR-06 | Badge notifikasi | Navbar lonceng | Count belum dibaca muncul | | ✔ | Lulus |

---

## Ringkasan Cakupan

| Kelompok | Jumlah Skenario | Otomatis | Manual |
|----------|-----------------|----------|--------|
| A. Autentikasi (FR-01) | 7 | 7 | 0 |
| B. Hak akses (FR-02) | 8 | 8 | 0 |
| C. Admin (FR-14) | 6 | 6 | 0 |
| D. Dosen persetujuan (S2-01) | 8 | 8 | 0 |
| E. Mitra seleksi (S2-02) | 8 | 8 | 0 |
| F. Mahasiswa kegiatan (S2-03) | 4 | 4 | 0 |
| G. Mahasiswa dokumen/laporan (S2-04) | 6 | 6 | 0 |
| H. Dosen bimbingan/penilaian (S2-05) | 4 | 4 | 0 |
| I. Mitra penilaian (S2-06) | 3 | 3 | 0 |
| J. Notifikasi (S2-07) | 4 | 4 | 0 |
| K. Integrasi (S2-09) | 2 | 2 | 0 |
| L. Non-fungsional (NFR) | 6 | 3 | 3 |
| **Total** | **60** | **57** | **3** |
