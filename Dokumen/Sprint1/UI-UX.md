# UI/UX Documentation - Sprint 1
**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri  
**Sprint:** 1 (Minggu 6-7)  
**Versi:** 1.0  
**Tanggal:** 2026-10-13  

## 1. Overview
- **Tujuan**: Membuat antarmuka yang mudah digunakan bagi 4 peran utama (Mahasiswa, Dosen, Mitra, Admin) untuk mengakses fitur-fungsional Sprint 1.
- **Referensi UI/UX**: [Figma Design](https://www.figma.com/make/gFlzRXZaxdrvPFI3IEfn8l/Web-based-Academic-Management-System?code-node-id=0-6&p=f&t=gCkDtI9hUTDUNVUo-0&fullscreen=1)

## 2. Wireframe & Flow

### 2.1 Alur Utama
1. **Login** → (Mahasiswa/Dosen/Mitra/Admin) → **Dashboard** → (Fitur sesuai peran)
2. **Logout** → kembali ke login

### 2.2 Wireframe Ringkasan

| Layar | Deskripsi | Elemen Utama |
|-------|-----------|--------------|
| **Login** | Halaman login pertama yang muncul ketika tidak terautentikasi | - Form email & password<br>- Tombol "Masuk"<br>- Link "Lupa Password"<br>- Link "Daftar" (untuk guest) |
| **Dashboard** | Halaman utama setelah login, menampilkan ringkasan peran | - Navigasi kiri (sidebar) dengan menu sesuai peran<br>- Card ringkasan (mis. jumlah program, peserta aktif)<br>- Aksen warna (biru/abu-abu) untuk aksi utama |
| **Halaman Program** | Daftar semua peluang program | - Pencarian/filter (perangkat, periode)<br>- List program dengan tombol "Daftar" |
| **Halaman Pendaftaran** | Form pendaftaran program | - Form terstruktur (nama, NIM, program pilihan, tanggal, berkas)<br>- Validasi client-side<br>- Tombol "Daftar" |
| **Halaman Pengajuan Penempatan** | Form pengajuan | - Form detail program/mitra<br>- Upload berkas (CV, surat rekomendasi)<br>- Status real-time (draft, pending review) |
| **Halaman Data Master (Admin)** | Manajemen data master | - Tabel pengguna dengan pencarian<br>- Tombol "Tambah", "Edit", "Aktifkan", "Nonaktifkan"<br>- Filter role |
| **Halaman Mitra** | Profil mitra & peluang | - Form profil mitra<br>- Tabel peluang dengan tombol "Edit", "Hapus", "Publish" |
| **Halaman Admin Dashboard** | Ringkasan admin | - Grafik statistik (jumlah mahasiswa, program aktif)<br>- Menu admin (users, programs, reports) |

### 2.3 Desain Visual
- **Warna Utama**: Biru (#19140035) untuk aksen, abu-abu (#FDFDFC) background, hitam untuk teks.
- **Font**: Instrument Sans (standar Laravel Blade).
- **Ikon**: Iconify atau Font Awesome (ikon user, program, upload, notifikasi).
- **Responsif**: Layout beralih dari 2 kolom (desktop) ke 1 kolom (mobile) dengan hamburger menu.

## 3. Navigation & Navigation Flow

| Halaman | Akses dari | Aksi utama |
|--------|-----------|------------|
| Login | - | Masuk, Lupa Password |
| Dashboard | Login | Navigasi ke modul sesuai peran |
| Program | Mahasiswa, Mitra | Cari, daftar program |
| Pendaftaran | Mahasiswa | Isi form, upload berkas, kirim |
| Pengajuan Penempatan | Mahasiswa | Isi formulir, upload, kirim |
| Admin Dashboard | Admin | Manajemen pengguna, data master |
| Admin Dashboard | Admin | Ringkasan statistik, laporan |

## 4. Responsifitas
- **Desktop**: 2 kolom (sidebar + content)
- **Tablet**: 1 kolom, menu dropdown
- **Mobile**: 1 kolom penuh, hamburger menu

## 5. Catatan Teknis
- Gunakan Tailwind CSS untuk utility classes.
- Pastikan semua input memiliki label terhubung (accessibility).
- Gunakan validation client-side (required, email format, file type/size).
- Simpan state UI di localStorage jika diperlukan untuk persistensi sementara.