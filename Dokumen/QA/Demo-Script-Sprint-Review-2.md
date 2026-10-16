# Demo Script — Sprint Review Sprint 2

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Durasi:** ±10 menit
**Build demo:** cabang `docs/qa-sprint-2` (S2-01…S2-09 di atas `main`)
**Sprint goal:** alur lengkap penempatan magang — persetujuan dosen (S2-01), seleksi mitra (S2-02), pencatatan kegiatan (S2-03), dokumen & laporan (S2-04), bimbingan & evaluasi dosen (S2-05), penilaian mitra (S2-06), notifikasi (S2-07), rekap admin (S2-08).

---

## Persiapan Sebelum Demo (lakukan 5 menit sebelumnya)

```bash
git checkout docs/qa-sprint-2
php artisan migrate:fresh --seed   # reset data demo
php artisan storage:link           # agar logo mitra tampil
php artisan serve
```

- Buka browser: jendela 1 (mode normal) & jendela 2 (incognito) untuk uji 2 peran sekaligus.
- Akun (sandi awal `password`): `admin@skp.test`, `dosen@skp.test`, `mitra@skp.test`, `mahasiswa@skp.test`.
- Siapkan 1 berkas contoh PDF/JPG (≤ 5 MB) untuk demo unggah berkas.

---

## Alur Demo

### 0:00–0:30 — Pembuka

- Sebutkan sprint goal & item yang didemokan (S2-01…S2-09) dan bahwa seluruhnya **lulus 175 uji otomatis / 555 assertion**.

### 0:30–2:00 — Dosen: Persetujuan Pengajuan (S2-01)

1. Login **dosen** → `/dosen/pengajuan`: tunjukkan **antrean pengajuan** yang ditujukan kepadanya, urut paling lama menunggu.
2. Buka detail pengajuan → tunjukkan identitas mahasiswa/prodi, mitra & program, status, catatan, berkas lampiran.
3. Tekan **Setujui** → status berubah **`disetujui_dosen`**, notifikasi mahasiswa muncul.
4. Tekan **Tolak** + isi alasan → status → `ditolak`, alasan tersimpan di catatan.
5. (Jendela dosen lain) coba buka pengajuan bukan miliknya → **403**.

### 2:00–3:30 — Mitra: Seleksi Peserta (S2-02)

1. Login **mitra** → `/mitra/program`: tunjukkan daftar program miliknya + tautan **Pelamar**.
2. `/mitra/program/{id}/pelamar`: tunjukkan **antrean pelamar** `disetujui_dosen` + sisa kuota.
3. Tekan **Terima** → status → `diterima_mitra`, kuota berkurang, notifikasi mahasiswa.
4. Coba terima melebihi kuota → **ditolak**, pesan "Kuota program sudah penuh".
5. Tekan **Tolak** + alasan → status → `ditolak`, alasan disimpan.
6. (Mitra lain) coba akses pelamar program bukan miliknya → **403**.

### 3:30–5:00 — Mahasiswa: Pencatatan Kegiatan & Dokumen (S2-03, S2-04)

1. Login **mahasiswa** → buka daftar kegiatan → **tambah kegiatan** (tanggal, uraian, bukti PDF ≤ 5 MB).
2. Ubah kegiatan sebelum dosen memberikan catatan → berhasil.
3. Unggah berkas laporan → tersimpan di penyimpanan privat (`storage/app/private`).
4. Coba unggah berkas > 5 MB → **ditolak**.
5. Buka tautan berkas di jendela tanpa hak → **403** (NFR-08).

### 5:00–6:30 — Dosen: Bimbingan & Evaluasi (S2-05)

1. Login **dosen** → daftar bimbingan mahasiswa yang dibimbing.
2. Buka kegiatan mahasiswa → **catat bimbingan** → kegiatan terkunci (tidak dapat diubah mahasiswa).
3. Isi **form penilaian dosen** → nilai tersimpan, dikaitkan pendaftaran.

### 6:30–7:30 — Mitra: Penilaian Mahasiswa (S2-06)

1. Login **mitra** → daftar penilaian mahasiswa yang diterimanya.
2. Isi form penilaian → nilai tersimpan.
3. (Mitra lain) coba nilai mahasiswa bukan milik programnya → **403**.

### 7:30–8:30 — Admin: Rekapitulasi & Notifikasi (S2-08, S2-07)

1. Login **admin** → `/admin/rekap`: tunjukkan tabel rekap per program / prodi / mitra + **ekspor CSV/Excel**.
2. Navbar: tunjukkan **badge count notifikasi belum dibaca** di ikon lonceng.
3. Buka halaman notifikasi → daftar notifikasi mahasiswa terkait perubahan status.

### 8:30–9:30 — Hasil Uji & Definition of Done

- Tampilkan `php artisan test` → **175 lulus / 555 assertion**.
- Tunjukkan `Dokumen/QA/Laporan-Uji-Sprint-2.md` & `Skenario-Uji-Sprint-2.md`.
- Sebutkan DoD §6.3: kode di branch & PR, uji lulus, **tampil baik di HP + desktop** (uji manual QA), `PROGRESS.md` diperbarui.

### 9:30–10:00 — Penutup & Sprint Berikutnya

- Ringkas: alur penutup magang (selesai, penilaian akhir) menyusul di sprint berikutnya.

---

## Antisipasi Masalah Demo

| Risiko | Mitigasi |
|--------|----------|
| Data demo kotor | `php artisan migrate:fresh --seed` sebelum mulai |
| Logo mitra tidak tampil | Jalankan `php artisan storage:link` |
| Berkas unggah gagal | Pastikan berkas ≤ 5 MB & format PDF/JPG/PNG/DOCX |
| Sesi peran tercampur | Gunakan jendela incognito terpisah per peran |
| Waktu melebihi 10 menit | Prioritaskan alur A–C (persetujuan → seleksi → kegiatan); skip rekap bila perlu |
