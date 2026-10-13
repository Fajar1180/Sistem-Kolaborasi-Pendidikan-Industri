# Skenario Uji Fungsi — Sprint 1

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Sprint:** Sprint 1 (Minggu 6–7)
**Build uji:** cabang `docs/uji-sprint-1` (integrasi `main` + `feature/mahasiswa-daftar`)
**Tanggal:** 2026-10-12

---

## Cara Pakai

- **Hasil aktual** diisi saat eksekusi. Nilai **Status**: `Lulus`, `Gagal`, `Belum diuji`.
- Kolom **Otomatis** menandai skenario yang sudah dicakup uji otomatis (`php artisan test`); lihat `Laporan-Uji-Sprint-1.md` untuk bukti.
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

## A. Autentikasi & Akun (FR-01)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-AUTH-01 | Masuk admin | Login `admin@skp.test` | Diarahkan ke dashboard admin | Lulus | ✔ `AuthenticationTest` | Lulus |
| TC-AUTH-02 | Masuk dosen | Login `dosen@skp.test` | Diarahkan ke area dosen (pengajuan) | Lulus | ✔ | Lulus |
| TC-AUTH-03 | Masuk mitra | Login `mitra@skp.test` | Diarahkan ke area mitra (peluang) | Lulus | ✔ | Lulus |
| TC-AUTH-04 | Masuk mahasiswa | Login `mahasiswa@skp.test` | Diarahkan ke katalog peluang | Lulus | ✔ | Lulus |
| TC-AUTH-05 | Kredensial salah | Login email benar + sandi salah | Tetap di login, pesan galat generik (tidak membocorkan email terdaftar/tidak) | Lulus | ✔ | Lulus |
| TC-AUTH-06 | Akun nonaktif | Login akun `is_active=false` | Ditolak, tidak dapat masuk | Lulus | ✔ | Lulus |
| TC-AUTH-07 | Keluar | Klik Keluar | Sesi berakhir, diarahkan ke halaman login | Lulus | ✔ | Lulus |
| TC-AUTH-08 | Lupa sandi | Minta tautan reset | Halaman konfirmasi; tautan reset dibuat | Lulus | ✔ `PasswordResetTest` | Lulus |
| TC-AUTH-09 | Atur ulang sandi | Buka tautan reset valid + sandi baru | Sandi berubah, dapat masuk dengan sandi baru | Lulus | ✔ | Lulus |
| TC-AUTH-10 | Token reset tidak valid | Buka tautan reset kedaluwarsa/ngawur | Ditolak dengan pesan galat | Lulus | ✔ | Lulus |

## B. Hak Akses Empat Peran (FR-02, matriks §7.7)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-RBAC-01 | Redirect per peran | Login tiap peran | Mendarat di area perannya masing-masing | Lulus | ✔ `RoleAccessTest` | Lulus |
| TC-RBAC-02 | Mahasiswa → area admin | Akses `/admin/users` sbg mahasiswa | `403 Forbidden` (halaman galat ramah) | Lulus | ✔ `AccessMatrixTest` | Lulus |
| TC-RBAC-03 | Dosen → area mitra | Akses `/mitra/program` sbg dosen | `403` | Lulus | ✔ | Lulus |
| TC-RBAC-04 | Mitra → area mahasiswa | Akses `/mahasiswa/pendaftaran` sbg mitra | `403` | Lulus | ✔ | Lulus |
| TC-RBAC-05 | Akses tanpa login | Buka URL area privat tanpa sesi | Dialihkan ke halaman login | Lulus | ✔ | Lulus |
| TC-RBAC-06 | Akun dinonaktifkan saat sesi aktif | Admin menonaktifkan akun yang sedang login, lalu akun beraksi | Sesi diputus, diarahkan ke login | Lulus | ✔ `EnsureUserIsActive` | Lulus |
| TC-RBAC-07 | Data milik orang lain | Mahasiswa A membuka/membatalkan pendaftaran mahasiswa B | `403` (policy kepemilikan) | Lulus | ✔ `PendaftaranMahasiswaTest` | Lulus |
| TC-RBAC-08 | Berkas privat pihak lain | Pengguna tak berwenang membuka `/dokumen/{id}` | `403` (hanya pemilik/dosen pembimbing/mitra penyelenggara/admin) | Lulus | ✔ `PengajuanTest` | Lulus |

## C. Admin — Pengguna & Data Master (FR-14)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-ADM-01 | Daftar pengguna | Buka `/admin/users` | Tabel pengguna; **pencarian** nama/email + **filter** peran & status bekerja | Lulus | ✔ `UserManagementTest` | Lulus |
| TC-ADM-02 | Tambah pengguna | Isi form + pilih peran | Pengguna dibuat; **profil otomatis** (`mahasiswas`/`dosens`/`mitras`) ikut dibuat | Lulus | ✔ | Lulus |
| TC-ADM-03 | Validasi per peran | Tambah mahasiswa tanpa NIM / dosen tanpa NIDN / mitra tanpa nama perusahaan | Validasi menolak dengan pesan Indonesia | Lulus | ✔ | Lulus |
| TC-ADM-04 | Ubah pengguna | Ubah nama/email/peran | Tersimpan; profil peran menyesuaikan | Lulus | ✔ | Lulus |
| TC-ADM-05 | Aktif/nonaktifkan | Nonaktifkan lalu aktifkan pengguna | Status berubah (soft, tanpa hapus data) | Lulus | ✔ | Lulus |
| TC-ADM-06 | Lindungi akun sendiri | Admin menonaktifkan/menghapus akunnya sendiri | Ditolak | Lulus | ✔ | Lulus |
| TC-ADM-07 | Hapus pengguna berelasi | Hapus pengguna yang punya pendaftaran/program/log | Hanya boleh dinonaktifkan, tidak dihapus (integritas data) | Lulus | ✔ | Lulus |
| TC-ADM-08 | Impor CSV valid | Unggah CSV header `nama,email,role` | Satu transaksi; ringkasan berhasil; sandi awal `password` | Lulus | ✔ `UserImportTest` | Lulus |
| TC-ADM-09 | Impor CSV header salah | Unggah CSV tanpa kolom wajib | Ditolak dengan pesan galat | Lulus | ✔ | Lulus |
| TC-ADM-10 | Impor baris tidak valid | CSV berisi baris bermasalah | Ringkasan menampilkan gagal + alasan per baris | Lulus | ✔ | Lulus |
| TC-ADM-11 | Template CSV | Unduh template | Berkas template terunduh | Lulus | ✔ | Lulus |
| TC-ADM-12 | Data master | CRUD program studi (kode unik) & periode akademik (hanya satu aktif) | Tersimpan; aturan unik/aktif ditegakkan | Lulus | ✔ `MasterDataTest` | Lulus |

## D. Mitra — Profil & Peluang (FR-11)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-MTR-01 | Profil perusahaan | Ubah nama/alamat/kontak/deskripsi | Tersimpan; selalu milik mitra yang masuk | Lulus | ✔ `ProfilMitraTest` | Lulus |
| TC-MTR-02 | Unggah logo | Unggah logo JPG/PNG/WEBP/SVG ≤ 5 MB | Logo tampil; logo lama diganti | Lulus | ✔ | Lulus |
| TC-MTR-03 | Validasi logo | Unggah berkas > 5 MB / format salah | Ditolak dengan pesan Indonesia | Lulus | ✔ | Lulus |
| TC-MTR-04 | Buat peluang | Buat peluang (status `draft`) | Tersimpan; hanya `published` terlihat mahasiswa | Lulus | ✔ `ProgramMitraTest` | Lulus |
| TC-MTR-05 | Validasi peluang | Kuota 0 / tanggal selesai ≤ mulai / jenis tidak valid | Ditolak | Lulus | ✔ | Lulus |
| TC-MTR-06 | Publikasikan | Tekan Publikasikan | Status `draft`→`published`; muncul di katalog mahasiswa | Lulus | ✔ | Lulus |
| TC-MTR-07 | Tutup peluang | Tekan Tutup | Status `published`→`closed`; pendaftaran baru berhenti, data lama utuh | Lulus | ✔ | Lulus |
| TC-MTR-08 | Kepemilikan peluang | Mitra lain mengubah/menutup peluang mitra ini | `403` (`ProgramPolicy`) | Lulus | ✔ | Lulus |
| TC-MTR-09 | Kuota vs peserta | Ubah kuota < jumlah peserta diterima | Ditolak (kuota ≥ peserta diterima) | Lulus | ✔ | Lulus |

## E. Mahasiswa — Katalog & Pendaftaran (FR-03)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-MHS-01 | Katalog published | Buka `/mahasiswa/program` | Hanya program `published`; `draft`/`closed` tersembunyi | Lulus | ✔ `ProgramKatalogTest` | Lulus |
| TC-MHS-02 | Pencarian | Cari kata pada judul/nama mitra | Hasil terfilter sesuai kata kunci | Lulus | ✔ | Lulus |
| TC-MHS-03 | Filter | Pilih filter jenis & periode | Hasil sesuai filter (periode tumpang-tindih) | Lulus | ✔ | Lulus |
| TC-MHS-04 | Pagination | Jelajah halaman katalog | 9 kartu per halaman, navigasi bekerja | Lulus | ✔ | Lulus |
| TC-MHS-05 | Detail program | Buka detail | Info lengkap; tombol Daftar (atau tautan status bila sudah mendaftar) | Lulus | ✔ | Lulus |
| TC-MHS-06 | Daftar program | Tekan Daftar → konfirmasi | Pendaftaran dibuat berstatus **`draf`** (§7.5) | Lulus | ✔ `PendaftaranMahasiswaTest` | Lulus |
| TC-MHS-07 | Cegah duplikat | Daftar program yang sama dua kali | Ditolak (unik `mahasiswa_id+program_id`) | Lulus | ✔ | Lulus |
| TC-MHS-08 | Kuota penuh | Daftar saat kuota habis | Ditolak + pesan | Lulus | ✔ | Lulus |
| TC-MHS-09 | Program tutup/draf | Daftar program `closed`/`draft` | `403` | Lulus | ✔ | Lulus |
| TC-MHS-10 | Pendaftaran Saya | Buka `/mahasiswa/pendaftaran` | Daftar pendaftaran + badge status berwarna (§7.5) | Lulus | ✔ | Lulus |
| TC-MHS-11 | Batalkan | Batalkan pendaftaran belum komitmen | Baris terhapus; boleh daftar ulang program sama | Lulus | ✔ | Lulus |
| TC-MHS-12 | Larang batalkan | Batalkan pendaftaran `diterima_mitra`/`berjalan`/`selesai` | Ditolak (`bisaDibatalkan()`) | Lulus | ✔ | Lulus |

## F. Mahasiswa — Pengajuan Penempatan (FR-04, NFR-08)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-PGJ-01 | Buka form | Buka form pengajuan pendaftaran `draf` | Form catatan + unggah berkas tampil | Lulus | ✔ `PengajuanTest` | Lulus |
| TC-PGJ-02 | Simpan draf | Isi catatan + lampirkan berkas, simpan | Tersimpan; tetap `draf` | Lulus | ✔ | Lulus |
| TC-PGJ-03 | Validasi berkas | Unggah format salah / > 5 MB | Ditolak (PDF/JPG/PNG/DOCX, maks 5 MB) — NFR-08 | Lulus | ✔ | Lulus |
| TC-PGJ-04 | Hapus berkas | Hapus berkas terlampir | Berkas terhapus dari daftar | Lulus | ✔ | Lulus |
| TC-PGJ-05 | Ajukan | Tekan **Ajukan Penempatan** | Status `draf`/`perlu_revisi` → **`diajukan`**; `diajukan_at` tercatat | Lulus | ✔ | Lulus |
| TC-PGJ-06 | Terkunci | Buka form setelah `diajukan` | Form terkunci (tidak dapat diubah mahasiswa) | Lulus | ✔ | Lulus |
| TC-PGJ-07 | Revisi | Status `perlu_revisi` (catatan reviewer tampil) → perbaiki → ajukan ulang | Berhasil diajukan ulang | Lulus | ✔ | Lulus |
| TC-PGJ-08 | Unduh berkas (pemilik) | Klik unduh berkas | Berkas terunduh dari penyimpanan privat | Lulus | ✔ | Lulus |
| TC-PGJ-09 | Berkas privat (NFR-08) | Akses berkas via URL tanpa hak | `403`/`404`; berkas tidak ter-serve publik | Lulus | ✔ | Lulus |
| TC-PGJ-10 | Jejak audit & notifikasi | Simpan/ajukan | Aksi tercatat di `activity_logs`; event `PendaftaranStatusBerubah` terpicu | Lulus | ✔ | Lulus |

## G. Non-Fungsional (NFR)

| ID | Skenario | Langkah | Hasil yang diharapkan | Hasil aktual | Otomatis | Status |
|----|----------|---------|------------------------|--------------|----------|--------|
| TC-NFR-01 | Responsif HP | Buka tiap halaman utama di 360px | Tata letak rapi, tanpa scroll horizontal | — | Manual | Belum diuji |
| TC-NFR-02 | Responsif desktop | Buka di ≥ 1366px | Tata letak rapi & konsisten | — | Manual | Belum diuji |
| TC-NFR-03 | Lintas browser | Chrome + 1 browser lain | Fungsi utama berjalan | — | Manual | Belum diuji |
| TC-NFR-04 | Jejak audit | Periksa `activity_logs` | Aksi penting tercatat (NFR-06) | Lulus | ✔ | Lulus |
| TC-NFR-05 | Keamanan sandi | Periksa penyimpanan sandi | Sandi ter-hash; sesi di-regenerate saat login | Lulus | ✔ | Lulus |
| TC-NFR-06 | Berkas privat | Periksa lokasi berkas | Berkas di `storage/app/private`, tidak ter-serve web (NFR-08) | Lulus | ✔ | Lulus |

---

## Ringkasan Cakupan

| Kelompok | Jumlah Skenario | Otomatis | Manual |
|----------|-----------------|----------|--------|
| A. Autentikasi (FR-01) | 10 | 10 | 0 |
| B. Hak akses (FR-02) | 8 | 8 | 0 |
| C. Admin (FR-14) | 12 | 12 | 0 |
| D. Mitra (FR-11) | 9 | 9 | 0 |
| E. Mahasiswa katalog/daftar (FR-03) | 12 | 12 | 0 |
| F. Mahasiswa pengajuan (FR-04) | 10 | 10 | 0 |
| G. Non-fungsional (NFR) | 6 | 3 | 3 |
| **Total** | **67** | **64** | **3** |
