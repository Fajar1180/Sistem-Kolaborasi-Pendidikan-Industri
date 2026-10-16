# Hasil Uji Sprint 2 — Sistem Kolaborasi Pendidikan & Industri

**Tanggal Testing:** [Diisi saat testing]  
**Tester:** Fazna Laisal Ramadhan, Riphan Romadlon  
**Environment:** Laragon (Windows 11), Chrome 119, PHP 8.2, Laravel 11.x  
**Database:** skp_db (MySQL 8.0)

---

## 1. Executive Summary

| Metric | Result |
|--------|--------|
| Total Test Cases | 50+ |
| Passed | [XX] |
| Failed | [XX] |
| Blocked | [XX] |
| Pass Rate | [XX%] |
| Critical Bugs | [XX] |
| High Bugs | [XX] |
| Medium Bugs | [XX] |
| Low Bugs | [XX] |

**Overall Status:** 🟢 PASS / 🟡 CONDITIONAL PASS / 🔴 FAIL

**Rekomendasi:** [Deploy ke staging / Fix bugs dulu / Perlu sprint tambahan]

---

## 2. Hasil Testing per Fitur

### S2-01: Persetujuan Dosen ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Dosen lihat pengajuan mahasiswa bimbingannya | ✅ PASS | |
| Dosen approve pengajuan → status jadi disetujui_dosen | ✅ PASS | |
| Dosen minta revisi → status jadi revisi_dosen | ✅ PASS | |
| Mahasiswa dapat notifikasi setelah approval | ✅ PASS | |
| Dosen tidak bisa approve pengajuan mahasiswa lain | ✅ PASS | |
| **Sub-total** | **5/5 PASS** | |

**Bugs Found:** None / [Link ke issue #XX]

---

### S2-02: Seleksi Mitra + Kuota ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mitra lihat pendaftaran status disetujui_dosen | ⏳ PENDING | |
| Mitra terima mahasiswa → status jadi diterima_mitra | ⏳ PENDING | |
| Kuota program berkurang setelah terima peserta | ⏳ PENDING | |
| Validasi error saat kuota penuh | ⏳ PENDING | |
| Mitra tolak mahasiswa → status ditolak_mitra | ⏳ PENDING | |
| Notifikasi ke mahasiswa (diterima/ditolak) | ⏳ PENDING | |
| **Sub-total** | **0/6 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-03: Logbook Kegiatan Mahasiswa ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mahasiswa tambah kegiatan baru | ⏳ PENDING | |
| Upload foto kegiatan (max 2MB) | ⏳ PENDING | |
| Edit kegiatan yang sudah ada | ⏳ PENDING | |
| Hapus kegiatan | ⏳ PENDING | |
| Validasi form (tanggal, judul, deskripsi) | ⏳ PENDING | |
| Dosen bisa lihat logbook mahasiswa (read-only) | ⏳ PENDING | |
| **Sub-total** | **0/6 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-04: Dokumen + Laporan + Flag TERLAMBAT ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mahasiswa upload proposal (PDF max 5MB) | ⏳ PENDING | |
| Mahasiswa upload laporan akhir (DOCX/PDF max 10MB) | ⏳ PENDING | |
| Flag TERLAMBAT muncul jika lewat deadline | ⏳ PENDING | |
| Validasi file size & extension | ⏳ PENDING | |
| Dosen + mitra bisa download dokumen | ⏳ PENDING | |
| **Sub-total** | **0/5 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-05: Bimbingan + Evaluasi Dosen ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Dosen tambah catatan bimbingan | ⏳ PENDING | |
| Mahasiswa lihat history bimbingan | ⏳ PENDING | |
| Rating 1-5 (opsional) | ⏳ PENDING | |
| Notifikasi ke mahasiswa setelah feedback | ⏳ PENDING | |
| Dosen tidak bisa edit bimbingan dosen lain | ⏳ PENDING | |
| **Sub-total** | **0/5 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-06: Penilaian Mitra ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mitra beri nilai mahasiswa (0-100) | ⏳ PENDING | |
| Feedback min 20 karakter | ⏳ PENDING | |
| Status pendaftaran jadi selesai setelah dinilai | ⏳ PENDING | |
| Validasi nilai max 100 | ⏳ PENDING | |
| Mahasiswa dapat notifikasi + lihat nilai | ⏳ PENDING | |
| **Sub-total** | **0/5 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-07: Notifikasi In-App + Email ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Badge count muncul di navbar (icon lonceng) | ✅ PASS | Already exists (line 28-35 layouts/app.blade.php) |
| Dropdown 5 notifikasi terbaru | ⏳ PENDING | |
| Klik notifikasi → redirect ke halaman terkait | ⏳ PENDING | |
| Email terkirim (jika SMTP on) | ⏳ SKIP | Dev env, no SMTP |
| Tandai dibaca → badge count berkurang | ⏳ PENDING | |
| Arsipkan notifikasi | ⏳ PENDING | |
| **Sub-total** | **1/5 PASS, 1 SKIP** | |

**Bugs Found:** [Diisi saat testing]

---

### S2-08: Rekap & Ekspor Admin ✅ / ⚠️ / ❌

| Test Case | Status | Notes |
|-----------|--------|-------|
| Rekap semua pendaftaran dengan filter | ⏳ PENDING | |
| Statistik card update sesuai filter | ⏳ PENDING | |
| Rekap per program | ⏳ PENDING | |
| Rekap per prodi | ⏳ PENDING | |
| Rekap per mitra | ⏳ PENDING | |
| Ekspor Excel (.csv) berhasil download | ⏳ PENDING | |
| CSV berisi data lengkap dengan header | ⏳ PENDING | |
| Pagination 20 items | ⏳ PENDING | |
| **Sub-total** | **0/8 PASS** | |

**Bugs Found:** [Diisi saat testing]

---

## 3. Regresi Sprint 1

### Autentikasi & Hak Akses

| Test Case | Status | Notes |
|-----------|--------|-------|
| Login 4 role berhasil | ⏳ PENDING | |
| Logout redirect ke login | ⏳ PENDING | |
| Access control: mahasiswa tidak akses /admin/* | ⏳ PENDING | |
| Access control: dosen tidak akses /mitra/* | ⏳ PENDING | |
| **Sub-total** | **0/4 PASS** | |

### Master Data (Admin)

| Test Case | Status | Notes |
|-----------|--------|-------|
| CRUD Pengguna | ⏳ PENDING | |
| CRUD Program Studi | ⏳ PENDING | |
| CRUD Periode Akademik | ⏳ PENDING | |
| Import CSV mahasiswa/dosen/mitra | ⏳ PENDING | |
| **Sub-total** | **0/4 PASS** | |

### Peluang Program

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mahasiswa lihat list program aktif | ⏳ PENDING | |
| Mahasiswa search/filter program | ⏳ PENDING | |
| Mitra buat program → publish | ⏳ PENDING | |
| Mitra edit/tutup program | ⏳ PENDING | |
| **Sub-total** | **0/4 PASS** | |

### Pendaftaran & Pengajuan

| Test Case | Status | Notes |
|-----------|--------|-------|
| Mahasiswa daftar program → status draf | ⏳ PENDING | |
| Mahasiswa lengkapi data → ajukan | ⏳ PENDING | |
| Status badge muncul dengan warna yang benar | ⏳ PENDING | |
| **Sub-total** | **0/3 PASS** | |

---

## 4. Bug List

### Critical Bugs (P0 - Blocker)

| ID | Title | Feature | Steps | Status |
|----|-------|---------|-------|--------|
| [#XX] | [Judul bug] | S2-01 | [Link ke issue] | 🔴 Open / 🟢 Fixed |

### High Bugs (P1)

| ID | Title | Feature | Steps | Status |
|----|-------|---------|-------|--------|
| - | - | - | - | - |

### Medium Bugs (P2)

| ID | Title | Feature | Steps | Status |
|----|-------|---------|-------|--------|
| - | - | - | - | - |

### Low Bugs (P3)

| ID | Title | Feature | Steps | Status |
|----|-------|---------|-------|--------|
| - | - | - | - | - |

---

## 5. Performance Testing

| Metric | Target | Actual | Status |
|--------|--------|--------|--------|
| Login page load | <2s | [XX]s | ⏳ |
| Dashboard load (with stats) | <3s | [XX]s | ⏳ |
| Rekap page load (100 records) | <3s | [XX]s | ⏳ |
| Ekspor CSV (1000 records) | <10s | [XX]s | ⏳ |
| File upload (5MB PDF) | <5s | [XX]s | ⏳ |

---

## 6. Browser & Device Compatibility

### Desktop Testing

| Browser | Version | Status | Notes |
|---------|---------|--------|-------|
| Chrome | 119 | ⏳ PENDING | Primary browser |
| Firefox | Latest | ⏳ PENDING | Secondary |
| Edge | Latest | ⏳ SKIP | Optional |

### Mobile Testing (Responsive)

| Device | Viewport | Status | Notes |
|--------|----------|--------|-------|
| iPhone SE | 375x667 | ⏳ PENDING | Chrome DevTools |
| iPad | 768x1024 | ⏳ PENDING | Tablet view |
| Desktop | 1920x1080 | ⏳ PENDING | Standard desktop |

---

## 7. Accessibility Check (WCAG 2.1)

| Criteria | Status | Notes |
|----------|--------|-------|
| Color contrast ratio 4.5:1 | ⏳ PENDING | Use axe DevTools |
| All images have alt text | ⏳ PENDING | |
| Forms have linked labels | ⏳ PENDING | `<label for="">` |
| Keyboard navigation works | ⏳ PENDING | Tab through forms |
| Screen reader compatible | ⏳ SKIP | Need NVDA/JAWS |

---

## 8. Known Issues (Won't Fix)

| Issue | Reason | Workaround |
|-------|--------|------------|
| [Contoh: Email notifikasi tidak terkirim di dev] | No SMTP in localhost | Use Mailtrap/MailHog untuk testing |

---

## 9. Sprint 2 Achievements

### Features Completed ✅
- [ ] S2-01: Persetujuan Dosen
- [ ] S2-02: Seleksi Mitra + Kuota
- [ ] S2-03: Logbook Kegiatan
- [ ] S2-04: Dokumen + TERLAMBAT
- [ ] S2-05: Bimbingan Dosen
- [ ] S2-06: Penilaian Mitra
- [ ] S2-07: Notifikasi In-App
- [ ] S2-08: Rekap Admin + Ekspor

### Sprint 2 Backlog (Deferred to Sprint 3)
- [ ] [Jika ada fitur yang belum selesai]

---

## 10. Rekomendasi untuk Sprint 3

### Must Fix Before Production
1. [Critical bug #XX - deskripsi singkat]
2. [High bug #YY - deskripsi singkat]

### Nice to Have
1. Loading spinner untuk form submit
2. Delete confirmation modal
3. Dark mode (opsional)

### Performance Optimization
1. Add database indexes (mahasiswa_id, program_id, status)
2. Eager load relationships (with('program.mitra'))
3. Cache rekap statistics (Cache::remember())

---

## 11. Lampiran

### Screenshots
- [Folder: Dokumen/Testing/Screenshots/Sprint-2/]
- Login-success.png
- Dashboard-admin.png
- Notifikasi-badge.png
- Rekap-ekspor.png

### Test Data Export
- [File: pendaftaran_test_data.csv]
- [File: users_test_accounts.csv]

---

**Disusun oleh:** Fazna Laisal Ramadhan (QA), Riphan Romadlon (QA)  
**Direview oleh:** Ikhsan (SA), Fajar (PM)  
**Tanggal:** [Diisi setelah testing selesai]  
**Status:** 🔴 Draft / 🟡 In Review / 🟢 Final
