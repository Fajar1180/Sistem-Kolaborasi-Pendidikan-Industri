# Progress Dokumentasi — QA

**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri — Kelompok 6
**Tim:** QA (Fazna) — dibantu System Analyst (Ikhsan) & Backend
**Sprint:** Sprint 1 (Minggu 6–7) / Sprint 2 (Minggu 8–9)

---

## Format Penulisan Progress

Setiap entri progress berisi:

- **Tanggal** (YYYY-MM-DD)
- **ID Pekerjaan** (sesuai Sprint Backlog: S1-01…S1-10 / S2-01…S2-10)
- **Judul Pekerjaan**
- **Cabang Git** (mis. `docs/uji-sprint-1`)
- **Komitmen yang telah dilakukan** (poin-poin)
- **Status** (`Belum mulai` / `Sedang mengerjakan` / `Selesai - ready for review` / `Blocker`)
- **Catatan** (kendala, temuan, link PR/issue terkait)

> Rujukan: `RULES.md` §2 — QA & Documentation mencatat pada `Dokumen/QA/PROGRESS.md`.

---

## Sprint 1

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S1-10 | Uji fungsi Sprint 1 dan dokumentasi | `docs/uji-sprint-1` | Sedang mengerjakan | Skenario uji, laporan uji otomatis (110/110 lulus), dan demo script siap. Uji manual perangkat (HP 360px) & lintas browser menunggu QA. Issue #10 |

---

## Sprint 2

| ID | Item Pekerjaan | Branch | Status | Progress |
|----|----------------|--------|--------|----------|
| S2-10 | Uji fungsi Sprint 2 dan dokumentasi | | Belum mulai | Issue #21 |

---

## Berkas Uji Sprint 1

| Berkas | Isi |
|--------|-----|
| `Dokumen/QA/Skenario-Uji-Sprint-1.md` | Daftar skenario uji (ID, langkah, hasil harapan, aktual, status) |
| `Dokumen/QA/Laporan-Uji-Sprint-1.md` | Laporan eksekusi (lingkungan, hasil otomatis, temuan, kesimpulan) |
| `Dokumen/QA/Demo-Script-Sprint-Review.md` | Naskah demo sprint review 10 menit |

---

## Catatan Harian (Log Detail)

## 2026-10-12

- **[S1-10] Uji fungsi Sprint 1 dan dokumentasi** — Cabang: `docs/uji-sprint-1`
  - Status: Sedang mengerjakan
  - Progress:
    - Menyusun **skenario uji Sprint 1** (FR-01…FR-04, FR-11, FR-14 + NFR-03/06/08) dalam format tabel ID, langkah, hasil harapan, hasil aktual, lolos/gagal — lihat `Skenario-Uji-Sprint-1.md`.
    - Mengintegrasikan cabang `feature/mahasiswa-daftar` (S1-08 & S1-09) ke atas `main` (S1-01…S1-07) sebagai **build uji Sprint 1 lengkap**; konflik teks pada `routes/web.php`, `lang/id/validation.php`, dan `Dokumen/Backend/PROGRESS.md` diselesaikan (gabung blok per-peran/atribut).
    - Menjalankan **uji otomatis** (`php artisan test`) pada build integrasi → **110 test lulus, 374 assertion** (lihat `Laporan-Uji-Sprint-1.md`).
    - Menyiapkan **demo script sprint review 10 menit** sesuai sprint goal §6.1 — lihat `Demo-Script-Sprint-Review.md`.
    - Belum dijalankan (menunggu QA): uji manual di HP (360px) + desktop, Chrome + 1 browser lain, dan penandaan temuan sebagai issue `bug` + `sprint-1`.
  - Catatan: Dokumen menuju PR `Closes #10`. Temuan baru (bila ada) dicatat sebagai issue terpisah berlabel `bug` + `sprint-1`, tidak ditumpuk di issue ini.
