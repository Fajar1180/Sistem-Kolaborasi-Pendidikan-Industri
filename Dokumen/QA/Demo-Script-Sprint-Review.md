# Demo Script — Sprint Review Sprint 1

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Durasi:** ±10 menit
**Build demo:** cabang `docs/uji-sprint-1` (S1-01…S1-09)
**Sprint goal:** fondasi aplikasi (autentikasi, hak akses empat peran, pengelolaan pengguna & data master) serta alur inti mitra–mahasiswa (peluang, pendaftaran, pengajuan penempatan).

---

## Persiapan Sebelum Demo (lakukan 5 menit sebelumnya)

```bash
git checkout docs/uji-sprint-1
php artisan migrate:fresh --seed   # reset data demo
php artisan storage:link           # agar logo mitra tampil
php artisan serve
```

- Buka browser: jendela 1 (mode normal) & jendela 2 (incognito) untuk uji 2 peran sekaligus.
- Akun (sandi awal `password`): `admin@skp.test`, `dosen@skp.test`, `mitra@skp.test`, `mahasiswa@skp.test`.
- Siapkan 1 berkas contoh PDF/JPG (≤ 5 MB) untuk demo unggah berkas pengajuan.

---

## Alur Demo

### 0:00–0:30 — Pembuka
- Sebutkan sprint goal & item yang didemokan (S1-01…S1-09) dan bahwa seluruhnya **lulus 110 uji otomatis**.

### 0:30–2:00 — Autentikasi & Hak Akses (FR-01, FR-02)
1. Login **admin** → mendarat di dashboard admin. Tunjukkan menu khusus admin.
2. Buka jendela incognito, login **mahasiswa** → mendarat di katalog peluang.
3. Sebagai mahasiswa, ketik URL `/admin/users` → tampil halaman **403** ramah (bukan stack trace).
4. Tunjukkan **lupa kata sandi** → halaman konfirmasi tautan reset.

### 2:00–3:30 — Admin: Pengguna & Data Master (FR-14)
1. `/admin/users`: cari "budi", filter peran *mahasiswa*, filter status.
2. **Tambah** pengguna baru (peran mahasiswa) → tunjukkan **profil mahasiswa otomatis** terbuat (NIM/prodi).
3. Coba **nonaktifkan akun sendiri** → ditolak.
4. `/admin/import`: unduh **template CSV**, unggah CSV 2 baris → tunjukkan **ringkasan hasil** (berhasil + alasan per baris bila gagal).
5. Data master: buat 1 **program studi** & 1 **periode akademik** (tunjukkan aturan satu periode aktif).

### 3:30–5:30 — Mitra: Profil & Peluang (FR-11)
1. Login **mitra** → `/mitra/profil`: ubah deskripsi + **unggah logo**.
2. `/mitra/program`: **buat peluang** baru (status `draft`).
3. **Publikasikan** → tunjukkan badge berubah `published` dan sisa kuota.
4. (Jendela mahasiswa) refresh katalog → peluang baru **muncul**.
5. Kembali ke mitra, coba ubah kuota < jumlah peserta diterima → **ditolak**. Tekan **Tutup** → status `closed`.

### 5:30–7:30 — Mahasiswa: Katalog, Daftar & Pengajuan (FR-03, FR-04)
1. (Mahasiswa) `/mahasiswa/program`: tunjukkan **pencarian**, **filter jenis/periode**, dan **pagination**.
2. Buka detail peluang → **Daftar** → pendaftaran dibuat berstatus **`draf`** (sesuai §7.5).
3. `/mahasiswa/pendaftaran`: tunjukkan **badge status**; buka **form pengajuan**.
4. Isi catatan/motivasi + **unggah berkas** (PDF ≤ 5 MB) → simpan.
5. Coba unggah berkas > 5 MB / format salah → **ditolak** (NFR-08).
6. Tekan **Ajukan Penempatan** → status berubah **`diajukan`** dan **form terkunci**.
7. Tunjukkan percobaan daftar program yang sama dua kali → **ditolak (duplikat)**.

### 7:30–8:30 — Non-Fungsional (NFR-08, NFR-06)
- **Berkas privat:** buka tautan `/dokumen/{id}` di jendela tanpa hak → **403**; jelaskan berkas tersimpan di `storage/app/private` (tidak ter-serve web).
- **Jejak audit:** buka `activity_logs` (mis. via admin/tinker) → tunjukkan aksi `daftar_program`, `simpan_pengajuan`, `ajukan_pendaftaran`, `publikasi_peluang` tercatat.

### 8:30–9:30 — Hasil Uji & Definition of Done
- Tampilkan `php artisan test` → **110 lulus / 374 assertion**.
- Tunjukkan `Dokumen/QA/Laporan-Uji-Sprint-1.md` & `Skenario-Uji-Sprint-1.md`.
- Sebutkan DoD §6.3: kode di branch & PR, uji lulus, **tampil baik di HP + desktop** (uji manual QA), `PROGRESS.md` diperbarui.

### 9:30–10:00 — Penutup & Sprint Berikutnya
- Ringkas: fondasi siap, alur inti mitra–mahasiswa berjalan.
- Sprint 2 berikutnya: persetujuan dosen (S2-01), seleksi mitra (S2-02), pencatatan kegiatan (S2-03), notifikasi (S2-07).

---

## Antisipasi Masalah Demo

| Risiko | Mitigasi |
|--------|----------|
| Data demo kotor | `php artisan migrate:fresh --seed` sebelum mulai |
| Logo tidak tampil | Jalankan `php artisan storage:link` |
| Berkas unggah gagal | Pastikan berkas ≤ 5 MB & format PDF/JPG/PNG/DOCX |
| Sesi peran tercampur | Gunakan jendela incognito terpisah per peran |
| Waktu melebihi 10 menit | Prioritaskan alur A (auth/RBAC) & F (pengajuan); skip impor CSV bila perlu |
