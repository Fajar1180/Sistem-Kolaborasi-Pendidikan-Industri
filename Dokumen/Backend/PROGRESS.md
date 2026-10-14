# Progress Dokumentasi — Backend

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri
**Tim:** Backend Developer (Ilham Almunawar)
**Sprint:** Sprint 1 (Minggu 6-7) / Sprint 2 (Minggu 8-9)

---

## Format Penulisan Progress

Setiap entri progress harus berisi:

- **Tanggal** (YYYY-MM-DD)
- **ID Pekerjaan** (sesuai Sprint Backlog: S1-01 sampai S1-10 / S2-01 sampai S2-10)
- **Judul Pekerjaan** (sesuai Sprint Backlog)
- **Cabang Git** (branch tempat pengerjaan, mis. `feature/auth`)
- **Komitmen yang telah dilakukan** (poin-poin)
- **Status** (`Belum mulai` / `Sedang mengerjakan` / `Selesai - ready for review` / `Blocker`)
- **Catatan** (kendala, hal yang perlu diskusi, link PR/issue terkait)

---

## Sprint 1

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S1-01 | Penyiapan repositori, lingkungan pengembangan, dan struktur proyek | `chore/project-setup` | Selesai - ready for review | Scaffold Laravel 13, `.env.example` MySQL Laragon, `.gitignore`, README setup. Issue #1 |
| S1-02 | Skema basis data dan migrasi awal | `feature/database-schema` | Selesai - ready for review | 14 tabel + model Eloquent + relasi + factory + seeder. Issue #2 |
| S1-03 | Autentikasi: masuk, keluar, dan atur ulang kata sandi | `feature/auth-roles` | Selesai - ready for review | Login/logout + lupa/atur ulang kata sandi + rate limit + cek akun aktif. Issue #3 |
| S1-04 | Hak akses berdasarkan empat peran | `feature/auth-roles` | Selesai - ready for review | Middleware `role` + `active`, area per peran, dashboard per peran. Issue #4 |
| S1-05 | Tata letak responsif dan komponen antarmuka dasar | | (Frontend) | Issue #5 |
| S1-06 | Admin: pengelolaan pengguna dan data master | `feature/admin-users-master` | Selesai - ready for review | CRUD pengguna 4 peran + profil otomatis, aktif/nonaktif, impor CSV, data master Program Studi & Periode Akademik, activity log. Issue #6 |
| S1-07 | Mitra: profil dan publikasi peluang program | `feature/mitra-peluang` | Selesai - ready for review | CRUD profil mitra + logo, CRUD peluang (draft/published/closed), publish/close, validasi kuota ≥ peserta diterima, policy kepemilikan. Issue #7 |
| S1-08 | Mahasiswa: melihat peluang dan mendaftar program | `feature/mahasiswa-daftar` | Selesai - ready for review | Katalog peluang published + pencarian (judul/mitra) + filter jenis/periode + pagination, detail program, aksi daftar (cegah duplikat & kuota penuh/closed), "Pendaftaran Saya" + badge status, batalkan pendaftaran. Issue #8 |
| S1-09 | Mahasiswa: pengajuan penempatan | `feature/pengajuan-penempatan` | Selesai - ready for review | Form pengajuan (catatan + lampiran berkas PDF/JPG/PNG/DOCX maks 5 MB ke storage privat), simpan draf, ajukan (draf/perlu_revisi → diajukan), revisi & ajukan ulang, unduh berkas ber-policy (NFR-08), activity log + event notifikasi. Status awal pendaftaran direfaktor jadi `draf` sesuai §7.5. Issue #9 |
| S1-10 | Uji fungsi Sprint 1 dan dokumentasi | | (QA) | Issue #10 |

---

## Sprint 2

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S2-01 | Dosen: persetujuan pengajuan penempatan | `feature/dosen-persetujuan` | Selesai - ready for review | Antrean pengajuan untuk dosen pembimbing (hanya milik sendiri, filter status) + halaman detail; aksi setujui/tolak/revisi (catatan wajib untuk tolak/revisi) dengan transisi status §7.5, jejak audit (NFR-06), dan notifikasi mahasiswa; penugasan dosen pembimbing otomatis saat diajukan; admin melihat semua (hanya-baca, §7.7). Issue #11 |
| S2-02 | Mitra: seleksi dan verifikasi peserta | `feature/mitra-seleksi` | Selesai - ready for review | Antrean pelamar per program (status `disetujui_dosen`) untuk mitra penyelenggara; aksi **terima** (→ `diterima_mitra`, dibatasi kuota secara transaksional) & **tolak** (→ `ditolak`, alasan wajib) dengan jejak audit (NFR-06) + notifikasi mahasiswa; transisi otomatis `diterima_mitra` → `berjalan` saat tanggal mulai program tiba (scheduler harian). Issue #12 |
| S2-03 | Mahasiswa: pencatatan kegiatan | | Belum mulai | Issue #13 |
| S2-04 | Mahasiswa: pengelolaan dokumen dan pengumpulan laporan | | Belum mulai | Issue #15 |
| S2-05 | Dosen: pemantauan, pembimbingan, dan evaluasi | | Belum mulai | Issue #16 |
| S2-06 | Mitra: penilaian mahasiswa | | Belum mulai | Issue #17 |
| S2-07 | Notifikasi dalam sistem dan email | | Belum mulai | Issue #18 |
| S2-08 | Admin: pengawasan program, rekapitulasi, dan ekspor laporan | | Belum mulai | Issue #19 |
| S2-09 | Integrasi antarmodul dan perbaikan temuan Sprint 1 | | Belum mulai | Issue #20 |
| S2-10 | Uji fungsi Sprint 2 dan dokumentasi | | (QA) | Issue #21 |

---

## Catatan Harian (Log Detail)

## 2026-10-12

- **[S1-01] Penyiapan repositori, lingkungan pengembangan, dan struktur proyek** — Cabang: `chore/project-setup`
  - Status: Selesai - ready for review
  - Progress:
    - Inisialisasi proyek Laravel 13 di root repo (`composer create-project laravel/laravel`).
    - `.env.example` diarahkan ke MySQL Laragon (`DB_CONNECTION=mysql`, `DB_DATABASE=skp_db`), `APP_NAME` dan locale `id`.
    - `.gitignore` diverifikasi & diperketat: `.env`, `/vendor`, `/node_modules`, cache, dan file editor/AI tidak ikut commit.
    - `README.md`: langkah setup lokal, setup MySQL Laragon, dan daftar akun hasil seeder.
    - Branch `develop` dibuat sebagai branch integrasi pengembangan (`main` hanya rilis via PR PM).
  - Catatan: PR menuju `develop` (`Closes #1`). Terverifikasi: `php artisan key:generate` + `php artisan migrate --seed` berhasil.

- **[S1-02] Skema basis data dan migrasi awal** — Cabang: `feature/database-schema`
  - Status: Selesai - ready for review
  - Progress:
    - Migrasi `users` ditambah kolom `role` (mahasiswa|dosen|mitra|admin) + `is_active`.
    - Tabel domain: `mahasiswas`, `dosens`, `mitras`, `programs`, `pendaftarans`, `kegiatans`, `dokumens`, `laporans`, `bimbingans`, `penilaian_dosens`, `penilaian_mitras`, `notifikasis`, `activity_logs`.
    - Relasi Eloquent (hasMany/belongsTo) lengkap untuk seluruh model; status `pendaftarans` mengikuti PRD §7.5.
    - Factory untuk semua model + `DatabaseSeeder` (1 admin, 2 dosen, 5 mahasiswa, 2 mitra, 3 program published + 1 draft, 8 pengajuan contoh tiap status).
    - Kuota program dijaga tidak minus (`unsignedInteger` + helper `sisaKuota()`).
  - Catatan: `php artisan migrate:fresh --seed` sukses dari nol; relasi diverifikasi via tinker. PR menuju `develop` (`Closes #2`).

- **[S1-03] Autentikasi: masuk, keluar, dan atur ulang kata sandi** — Cabang: `feature/auth-roles`
  - Status: Selesai - ready for review
  - Progress:
    - Controller `AuthenticatedSessionController` (masuk, keluar), `PasswordResetLinkController` (lupa kata sandi), `NewPasswordController` (atur ulang).
    - `LoginRequest`: validasi + throttle 5 percobaan + pesan galat generik (tidak membocorkan email terdaftar/tidak) + cek `is_active`.
    - Kata sandi ter-hash (`password` => `hashed`); sesi di-regenerate saat masuk & di-invalidasi saat keluar.
    - **Redirect setelah masuk menyesuaikan peran** (`User::homeRouteName()`): admin → data pengguna, dosen → pengajuan, mitra → peluang, mahasiswa → peluang.
    - Pesan validasi & autentikasi **berbahasa Indonesia** (`lang/id/auth.php`, `passwords.php`, `validation.php`).
    - View `auth/login`, `auth/forgot-password`, `auth/reset-password` (Tailwind, responsif) + layout guest.
  - Catatan: 8 feature test autentikasi lulus. PR `Closes #3`.

- **[S1-04] Hak akses berdasarkan empat peran** — Cabang: `feature/auth-roles`
  - Status: Selesai - ready for review
  - Progress:
    - Middleware `EnsureUserHasRole` (`role:admin,dosen,mitra,mahasiswa`) → 403 bila peran tidak berwenang.
    - Middleware `EnsureUserIsActive` (`active`) → akun nonaktif dikeluarkan & diarahkan ke login.
    - Policy per resource: `ProgramPolicy`, `PendaftaranPolicy` (kepemilikan data + peran, sesuai matriks §7.7).
    - Halaman **Akun Saya** (`/profile`) untuk semua peran: ubah nama & kata sandi sendiri; **peran/status aktif tidak dapat diubah** dari form.
    - Halaman galat ramah `resources/views/errors/403|404|419.blade.php` (tanpa stack trace).
    - Dashboard menyesuaikan peran; grup rute area tiap peran (`/admin/*`, `/dosen/*`, `/mitra/*`, `/mahasiswa/*`).
  - Catatan: 6 feature test profil + 5 test matriks akses lulus (total suite: 32 test, 93 assertion). PR `Closes #4`.

- **[S1-06] Admin: pengelolaan pengguna dan data master** — Cabang: `feature/admin-users-master`
  - Status: Selesai - ready for review
  - Progress:
    - **Pengguna (FR-14):** `/admin/users` — daftar dengan pencarian (nama/email) + filter peran & status, tambah, ubah, aktif/nonaktifkan (soft), dan hapus.
    - **Profil otomatis:** trait `MembuatProfilPengguna` membuat/memperbarui baris `mahasiswas`/`dosens`/`mitras` sesuai peran yang dipilih, sehingga admin tidak membuat data ganda secara manual.
    - **Validasi per peran:** NIM/prodi/angkatan wajib untuk mahasiswa, NIDN untuk dosen, nama perusahaan untuk mitra; `prodi` divalidasi terhadap data master `program_studis` bila sudah terisi.
    - **Perlindungan data:** admin tidak dapat menonaktifkan/menghapus akunnya sendiri; akun yang sudah punya relasi (pendaftaran/program/notifikasi/activity log) hanya boleh dinonaktifkan, tidak dihapus (integritas data).
    - **Impor CSV:** `/admin/import` — validasi header (`nama`, `email`, `role`), validasi per baris, satu transaksi `DB::transaction`, ringkasan hasil (berhasil/gagal + alasan per baris), template CSV dapat diunduh, kata sandi awal `password`.
    - **Data master:** CRUD `program_studis` (kode unik, jenjang, aktif) dan `periode_akademiks` (rentang tanggal, hanya satu periode aktif).
    - **Jejak audit (NFR-06):** kolom `catatan` pada `activity_logs` + helper `ActivityLog::catat()`; setiap aksi penting (buat/ubah/nonaktifkan/hapus pengguna, perubahan data master, impor) tercatat.
    - **UI:** halaman Blade responsif + menu navigasi khusus admin (Pengguna, Program Studi, Periode Akademik, Impor CSV).
  - Catatan: suite total 59 test / 184 assertion lulus (`php artisan test`); `migrate:fresh --seed` sukses; Pint bersih pada berkas yang dikerjakan. PR menuju `develop` (`Closes #6`).

## 2026-10-13

- **[DOKUMEN-SA] SRS Sprint 1** — Cabang: `feature/s1-01-setup-and-auth`
  - Status: Selesai - ready for review
  - Progress:
    - Dokumen SRS Sprint 1 selesai di `Dokumen/Sprint1/SPRINT-1-SRS.md`.
    - Memuat FR Sprint 1 (FR-01, FR-02, FR-03, FR-04, FR-11, FR-14) + NFR-01..08 + kriteria penerimaan + Definition of Done.
  - Catatan: Acuan implementasi & pengujian Sprint 1 untuk seluruh tim.

- **[DOKUMEN-SA] API Specification Sprint 1** — Cabang: `feature/s1-01-setup-and-auth`
  - Status: Selesai - ready for review
  - Progress:
    - Dokumen API selesai di `Dokumen/Sprint1/API-SPECIFICATION.md`.
    - Endpoint lengkap: auth, users, programs, mitra, pendaftaran, pengajuan penempatan, persetujuan dosen, seleksi mitra, notifikasi, audit log.
    - Format request/response JSON, middleware & keamanan (Sanctum, role, throttle, validasi).
  - Catatan: Kontrak data backend–frontend; perubahan endpoint wajib didokumentasikan di sini.

- **[DOKUMEN-SA] UI/UX Sprint 1** — Cabang: `feature/s1-01-setup-and-auth`
  - Status: Selesai - ready for review
  - Progress:
    - Dokumen UI/UX selesai di `Dokumen/Sprint1/UI-UX.md`.
    - Wireframe per layar (login, dashboard, program, pendaftaran, pengajuan, admin, mitra), navigasi, skema warna, responsif 3 breakpoint.
  - Catatan: Mengacu Figma (link di dokumen); frontend menyesuaikan bila ada revisi desain.

- **[DOKUMEN-SA] PRD Ringkas** — Cabang: `feature/s1-01-setup-and-auth`
  - Status: Selesai - ready for review
  - Progress:
    - Dokumen PRD ringkas selesai di `Dokumen/PRD.md` (rangkuman Bagian 7 dokumen perencanaan).
    - Memuat ringkasan produk, pengguna, alur status, FR/NFR, matriks hak akses, data utama, teknologi.
  - Catatan: Referensi cepat tim; PRD lengkap tetap pada dokumen perencanaan utama.
- **[S1-07] Mitra: profil dan publikasi peluang program** — Cabang: `feature/mitra-peluang`
  - Status: Selesai - ready for review
  - Progress:
    - **Profil perusahaan (`/mitra/profil`):** ubah nama, alamat, kontak, deskripsi, dan **unggah logo** (JPG/PNG/WEBP/SVG, maks 5 MB); logo lama dihapus saat diganti. Profil selalu milik mitra yang masuk — tidak ada akses silang antar mitra.
    - **Peluang program (`/mitra/program`):** daftar milik sendiri (dengan badge status, sisa kuota, jumlah pendaftar), buat, ubah, **publikasikan**, dan **tutup**.
    - **Status peluang:** `draft` → `published` → `closed`. Hanya `published` yang terlihat mahasiswa (sesuai `ProgramPolicy::view`); `closed` menghentikan pendaftaran baru namun data lama tetap utuh.
    - **Validasi:** kuota ≥ 1, `tanggal_selesai` harus setelah `tanggal_mulai`, jenis program dari daftar tetap, dan **kuota tidak boleh kurang dari jumlah peserta yang sudah diterima** saat mengubah (menggunakan `Program::kuotaTerpakai()`).
    - **Kepemilikan (policy):** `ProgramPolicy@update` memastikan mitra hanya dapat mengubah/mempublikasikan/menutup peluangnya sendiri; mitra lain mendapat 403.
    - **Jejak audit (NFR-06):** aksi `buat_peluang`, `ubah_peluang`, `publikasi_peluang`, `tutup_peluang`, dan `ubah_profil_mitra` tercatat di `activity_logs`.
    - **UI:** menu navigasi khusus mitra (Profil Perusahaan, Peluang Saya) + kartu dashboard.
  - Catatan: 18 feature test baru (profil + peluang) lulus; suite total 77 test / 256 assertion. `php artisan storage:link` dijalankan agar logo tersaji. PR menuju `main` (`Closes #7`) — `develop` sudah dihapus setelah rilis Sprint 1 (#26), sehingga `main` menjadi target integrasi.

- **[S1-08] Mahasiswa: melihat peluang dan mendaftar program** — Cabang: `feature/mahasiswa-daftar`
  - Status: Selesai - ready for review
  - Progress:
    - **Katalog peluang (`/mahasiswa/program`):** hanya program `published` (draf/closed tersembunyi), kartu menampilkan logo-mitra, judul, jenis, sisa kuota, periode; **pencarian** judul/mitra, **filter** jenis & periode (bulan tumpang-tindih), dan **pagination** 9 per halaman.
    - **Detail program (`/mahasiswa/program/{program}`):** dijaga `ProgramPolicy::view` (hanya published untuk mahasiswa); menampilkan kuota tersisa & tombol Daftar (atau tautan status bila sudah mendaftar).
    - **Daftar program:** halaman konfirmasi → simpan pendaftaran berstatus `diajukan` (`diajukan_at` tercatat). Cegah: program closed/draft (403), **kuota penuh** (redirect + pesan), dan **duplikat** (1 mahasiswa 1 pendaftaran per program — sesuai constraint unik `mahasiswa_id+program_id`); cadangan race condition ditangkap via `UniqueConstraintViolationException` + `lockForUpdate`.
    - **Pendaftaran Saya (`/mahasiswa/pendaftaran`):** daftar milik sendiri dengan **badge status berwarna** (§7.5: draf/diajukan/perlu_revisi/disetujui_dosen/ditolak/diterima_mitra/berjalan/selesai) + empty-state.
    - **Batalkan pendaftaran:** hanya bila belum dikomitmenkan (`Pendaftaran::bisaDibatalkan()` — bukan diterima/berjalan/selesai); pembatalan menghapus baris sehingga mahasiswa boleh mendaftar ulang program yang sama.
    - **Kepemilikan:** `PendaftaranPolicy` memastikan mahasiswa hanya melihat/membatalkan pendaftarannya sendiri (milik lain → 403).
    - **Jejak audit (NFR-06):** aksi `daftar_program` & `batal_pendaftaran` tercatat di `activity_logs`.
    - **Ketahanan:** akun mahasiswa tanpa profil `mahasiswas` tetap dapat membuka katalog & halaman "Pendaftaran Saya" (degradasi anggun, bukan 403) — konsisten dengan perlakuan mitra di S1-07.
  - Catatan: 18 feature test baru (katalog 8 + pendaftaran 10) lulus; suite total 77 test / 251 assertion. Pint bersih. PR menuju `main` (`Closes #8`) — `develop` sudah dihapus setelah rilis Sprint 1 (#26). Catatan integrasi: PR ini berpotensi sedikit bentrok di `routes/web.php` & `layouts/app.blade.php` dengan PR #27 (S1-07) karena sama-sama mengganti blok per-peran yang berdekatan; resolusi trivial (gabung dua blok per peran).

- **[S1-09] Mahasiswa: pengajuan penempatan** — Cabang: `feature/pengajuan-penempatan` (ditumpuk di atas `feature/mahasiswa-daftar` / PR #28 yang belum di-merge)
  - Status: Selesai - ready for review
  - Progress:
    - **Form pengajuan (`/mahasiswa/pendaftaran/{pendaftaran}/pengajuan`):** mahasiswa mengisi **catatan/motivasi** dan **melampirkan berkas** (PDF/JPG/PNG/DOCX, maks 5 MB per berkas, maks 5 berkas) — isian ditentukan SA.
    - **Refaktor status awal (§7.5):** "Daftar program" (S1-08) kini membuat pendaftaran berstatus **`draf`** (bukan `diajukan`); mahasiswa melengkapi formulir lalu menekan **Ajukan Penempatan** (`draf`/`perlu_revisi` → `diajukan`, `diajukan_at` tercatat). Status terkunci dari mahasiswa setelah diajukan.
    - **Ubah & ajukan ulang:** selama status `draf`/`perlu_revisi` (`Pendaftaran::bisaDieditMahasiswa()`) formulir dapat disimpan/diubah; setelah `diajukan` tidak dapat diubah.
    - **Revisi dosen:** status `perlu_revisi` menampilkan catatan reviewer (`pendaftarans.catatan`) dan mengizinkan perbaikan + ajukan ulang; kolom baru `catatan_mahasiswa` menyimpan catatan mahasiswa.
    - **Berkas privat (NFR-08):** berkas disimpan di disk `local` (`storage/app/private`, tidak ter-serve web); unduhan melalui `DokumenController` yang dijaga `DokumenPolicy` — hanya **pemilik, dosen pembimbing terkait, mitra penyelenggara, dan admin** (peran lain 403).
    - **Jejak audit & notifikasi:** aksi `simpan_pengajuan` & `ajukan_pendaftaran` tercatat di `activity_logs`; event `PendaftaranStatusBerubah` dipicu setiap perubahan status (konsumsi notifikasi S2-07).
    - **Perbaikan kecil:** flash `session('error')` kini tampil di layout (sebelumnya hanya `status`).
  - Catatan: 14 feature test baru (form/draf/berkas/hapus/ajukan/revisi + akses unduh privat: pemilik/mitra/dosen/admin vs lain) lulus; suite total 91 test / 298 assertion. Pint bersih. PR menuju `feature/mahasiswa-daftar` (ditumpuk; `Closes #9`) — otomatis berpindah base ke `main` saat PR #28 di-merge.

## 2026-10-13

- **[Perbaikan dokumen] Sisa penanda konflik Git pada `Dokumen/Backend/PROGRESS.md`** — menghapus penanda `<<<<<<<` / `=======` / `>>>>>>>` yang tertinggal dari merge PR #28 di `main` dan mengembalikan baris tabel S1-07/S1-08/S1-09 yang hilang. Tidak ada perubahan kode.

- **[S2-01] Dosen: persetujuan pengajuan penempatan** — Cabang: `feature/dosen-persetujuan`
  - Status: Selesai - ready for review
  - Progress:
    - **Antrean pengajuan (`/dosen/pengajuan`):** dosen pembimbing hanya melihat pengajuan yang **ditujukan kepadanya** (`dosen_pembimbing_id`); filter kategori (menunggu/perlu_revisi/riwayat). Akun dosen tanpa profil `dosens` diberi pesan pengarahan (degradasi anggun), konsisten S1-07/S1-08.
    - **Detail & keputusan (`/dosen/pengajuan/{pendaftaran}`):** menampilkan identitas mahasiswa/prodi, mitra & program, status, catatan mahasiswa, dan berkas lampiran (tautan unduh ber-policy). Aksi: **Setujui**, **Tolak**, **Revisi**.
    - **Transisi status (§7.5):** setujui → `disetujui_dosen` (`disetujui_dosen_at`), tolak → `ditolak`, revisi → `perlu_revisi`. `catatan` **wajib** untuk tolak & revisi ("Alasan penolakan wajib diisi." / "Catatan revisi wajib diisi."). Aksi idempoten — pengajuan yang sudah diputus tidak diproses ulang.
    - **Kepemilikan & akses:** `PendaftaranPolicy@putuskanDosen` — hanya dosen pembimbing terkait dan hanya saat status menunggu keputusan (`diajukan`/`perlu_revisi`); dosen lain 403. Admin hanya dapat **melihat** seluruh pengajuan (`/admin/pengajuan`, hanya-baca) sesuai matriks §7.7 — tidak berhak memutuskan.
    - **Penugasan pembimbing:** saat mahasiswa menekan "Ajukan Penempatan", sistem menugaskan dosen pembimbing otomatis (`Dosen::pilihPembimbing()` — utamakan prodi sama, lalu beban bimbingan paling sedikit).
    - **Jejak audit & notifikasi:** aksi `setujui_pengajuan`/`tolak_pengajuan`/`minta_revisi_pengajuan` tercatat di `activity_logs` (NFR-06); setiap keputusan membuat `Notifikasi` untuk mahasiswa dan memicu event `PendaftaranStatusBerubah` (dikonsumsi email pada S2-07).
    - **UI:** badge status bersama `resources/views/partials/status_badge.blade.php` (8 status §7.5) dipakai ulang di area mahasiswa; menu navigasi dosen & admin ditambah.
  - Catatan: 13 feature test baru (`tests/Feature/Dosen/PersetujuanPengajuanTest.php`) lulus; suite total 123 test / 425 assertion. Pint bersih. PR menuju `main` (`Closes #11`).

- **[S2-02] Mitra: seleksi dan verifikasi peserta** — Cabang: `feature/mitra-seleksi`
  - Status: Selesai - ready for review
  - Progress:
    - **Antrean pelamar (`/mitra/program/{program}/pelamar`):** mitra penyelenggara melihat pelamar berstatus **`disetujui_dosen`** (sudah lolos persetujuan dosen, §7.5/FR-12) untuk programnya, diurutkan paling lama menunggu; menampilkan identitas & prodi mahasiswa, waktu persetujuan dosen, serta sisa kuota. Program mitra lain → 403 (`ProgramPolicy@update`); peran lain ditolak middleware `role:mitra`.
    - **Terima peserta:** `disetujui_dosen` → `diterima_mitra` (`diterima_mitra_at` tercatat). **Dibatasi kuota secara transaksional** — baris program dikunci `lockForUpdate` lalu dicek `Program::kuotaPenuh()` sehingga penerimaan paralel tidak dapat melewati kuota; bila penuh, aksi ditolak dengan pesan "Kuota program sudah penuh".
    - **Tolak peserta:** `disetujui_dosen` → `ditolak`; **alasan wajib** (`KeputusanMitraRequest` — "Alasan penolakan wajib diisi."), disimpan pada `pendaftarans.catatan`.
    - **Kepemilikan & akses:** `PendaftaranPolicy@seleksiMitra` — hanya mitra penyelenggara program dan hanya saat status menunggu seleksi (`bisaDiseleksiMitra()`); mitra lain / status lain → 403.
    - **Transisi ke berjalan (§7.5 "sistem, sesuai periode"):** perintah `program:mulai-berjalan` mengubah `diterima_mitra` → `berjalan` bila `programs.tanggal_mulai` sudah tiba; dijadwalkan harian (`routes/console.php`, `Schedule::command(...)->dailyAt('01:00')`). Transisi ke `selesai` menyusul pada S2-05/S2-06 (bergantung penilaian).
    - **Jejak audit & notifikasi:** aksi `terima_peserta` / `tolak_peserta` / `mulai_berjalan` tercatat di `activity_logs` (NFR-06); setiap keputusan membuat `Notifikasi` untuk mahasiswa ("Diterima mitra"/"Ditolak mitra"/"Program mulai berjalan") dan memicu event `PendaftaranStatusBerubah` (dikonsumsi email pada S2-07).
    - **UI & seeder:** halaman `mitra/pelamar/index.blade.php` (badge kuota + form terima/tolak) dan tautan "Pelamar" pada daftar program mitra; seeder menambah 2 pelamar `disetujui_dosen` pada program pertama agar antrean seleksi dapat didemokan.
  - Catatan: 13 feature test baru (`tests/Feature/Mitra/SeleksiMitraTest.php`) lulus; suite total 136 test / 465 assertion. Pint bersih. PR menuju `main` (`Closes #12`).
## 2026-10-14 (2)
- [S2-09] Integrasi antarmodul dan perbaikan temuan Sprint 1 — Cabang: `fix/integrasi-sprint1`
  - Status: Selesai - ready for review
  - Progress:
    - Standardisasi error handling: semua controller pakai `with('error', ...)` konsisten
    - Perbaiki UserController, UserImportController (hapus `withErrors`, pakai `with('error')`)
    - Format pesan error seragam bahasa Indonesia
  - Catatan: Tidak ada bug kritis sprint-1 di GitHub issues. Fokus pembersihan inkonsistensi.
