# Analisis QA & Rekomendasi Sistem Analis
**Sprint:** 2 (QA Phase)  
**Assigned:** 1khsan2610 (System Analis)  
**Issues:** #43, #44, #45, #46  
**Tanggal:** 2026-10-16  

---

## 1. #45 — Landing Page / Masih Default Laravel Welcome

### Analisis
Route `/` menampilkan template default Laravel (welcome.blade.php), bukan landing khusus aplikasi. Kesan pertama buruk untuk pengunjung/mitra eksternal.

### Keputusan Desain
Sistem ini adalah **internal-only** (akun dibuat admin untuk mahasiswa/dosen):
- Mahasiswa & Dosen: dari internal kampus, akun sudah ada (via admin)
- Mitra: eksternal industri, butuh registrasi mandiri
- User baru: harus login (akun dibuat admin) atau daftar sebagai mitra

**Opsi Rekomendasi:**
- **Opsi A (Recommended):** Redirect `/` → `/login` (1 baris, paling murah) → cocok jika internal-only
- **Opsi B:** Buat landing page publik dengan nama aplikasi, info mitra, CTA login/daftar mitra

### Keputusan: **Opsi A (Redirect)**
Alasan: 
- Sistem fokus role-based (mahasiswa, dosen, mitra, admin)
- User tidak punya akses tanpa login
- Landing publik bisa ditambah sprint berikutnya jika ada kebutuhan marketing

### Implementasi
```php
// routes/web.php
Route::redirect('/', '/login');
```

### Criteria Acceptance
- ✅ GET / → redirect ke /login (302)
- ✅ Tidak ada warning/error di logs
- ✅ PR dibuat, reviewed, merged

---

## 2. #44 — Route /register 404 — Tidak Ada Registrasi Mandiri

### Analisis
GET `/register` → 404. Tidak ada RegisteredUserController di routes. Dampak: user baru tidak bisa daftar mandiri; akun harus dibuat admin.

### Keputusan Desain
Berdasarkan #45 (internal-only), registrasi ada 2 alur:

1. **Mahasiswa & Dosen:** Akun dibuat admin (via `/admin/users` atau import CSV)
   - Route `/register` tidak diperlukan
   - Pesan di login: "Belum punya akun? Hubungi admin kampus."

2. **Mitra Industri:** Registrasi mandiri
   - Tambah route `/register` + form untuk registrasi mitra
   - Verifikasi email sebelum active
   - Admin approve mitra sebelum bisa login penuh

### Keputusan: **Implementasikan Registrasi Mitra (Opsi 2)**
Alasan:
- Mitra eksternal tidak bisa daftar di admin → registrasi mandiri diperlukan
- Sistem sudah ada tabel `mitra`, migrasi, model `Mitra`
- Fitur ini penting untuk onboarding mitra baru

### Implementasi

**a) Buat RegisteredMitraController**
```php
namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;

class RegisteredMitraController extends Controller
{
    public function create()
    {
        return view('auth.register-mitra');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'mitra',
            'active' => false, // Admin approve
        ]);

        Mitra::create([
            'user_id' => $user->id,
            'nama_perusahaan' => $validated['company'],
            'kontak_person' => $validated['name'],
            'no_telp' => $validated['phone'],
        ]);

        return redirect('/login')->with('status', 'Akun mitra created. Tunggu approval admin.');
    }
}
```

**b) Add Route**
```php
// routes/web.php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // NEW
    Route::get('/register-mitra', [RegisteredMitraController::class, 'create'])->name('register-mitra');
    Route::post('/register-mitra', [RegisteredMitraController::class, 'store']);
});
```

**c) Update Login Page**
```blade
<!-- Tombol/Link Daftar Mitra -->
<p class="text-center text-sm">
    Mitra industri? <a href="{{ route('register-mitra') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
</p>
<p class="text-center text-sm text-gray-600">
    Mahasiswa/Dosen? Hubungi admin kampus untuk akun.
</p>
```

**d) Create Blade: resources/views/auth/register-mitra.blade.php**
```blade
<x-guest-layout>
    <x-auth-card>
        <h2 class="text-2xl font-bold mb-6">Daftar Sebagai Mitra Industri</h2>
        
        <form method="POST" action="{{ route('register-mitra') }}">
            @csrf
            
            <div>
                <label for="name">Nama Kontak Person</label>
                <input type="text" name="name" required autofocus />
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="company">Nama Perusahaan</label>
                <input type="text" name="company" required />
                @error('company') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="email">Email Perusahaan</label>
                <input type="email" name="email" required />
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="phone">Nomor Telepon</label>
                <input type="tel" name="phone" required />
                @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required />
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required />
            </div>
            
            <button type="submit" class="btn-primary w-full">Daftar</button>
        </form>
        
        <p class="text-center text-sm mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600">Login</a>
        </p>
    </x-auth-card>
</x-guest-layout>
```

### Criteria Acceptance
- ✅ GET `/register-mitra` → form daftar mitra (200)
- ✅ POST `/register-mitra` dengan data valid → user & mitra created, active=false, redirect login + status message
- ✅ Validasi email duplicate → error "Email sudah terdaftar"
- ✅ Password confirmation → error jika tidak cocok
- ✅ Login page menampilkan link "Daftar Mitra" + pesan "Mahasiswa/Dosen hubungi admin"
- ✅ Admin dapat approve mitra via `/admin/users` (ubah active = true)
- ✅ PR dibuat, reviewed, merged

---

## 3. #43 — Code Review & UX Assessment

### Status Aplikasi (dari issue #43)
✅ Arsitektur Laravel solid  
✅ Business logic matang  
✅ Frontend fungsional  
⚠️ UI/UX konsistensi kurang  
⚠️ Database missing indexes  
⚠️ Error handling belum komprehensif  
⚠️ N+1 queries untested  
⚠️ Testing 0% coverage  
⚠️ Accessibility untested  

### Action Plan Sistem Analis

#### **Priority 1 (CRITICAL — Sprint 2 End)**

| No. | Task | Effort | Owner | Deadline | Status |
|-----|------|--------|-------|----------|--------|
| 1.1 | Database: Tambah indexes & FK cascade explicit | 2h | Backend | 2026-10-18 | TODO |
| 1.2 | Blade components: standardisasi .btn, .card, .alert | 4h | Frontend | 2026-10-18 | TODO |
| 1.3 | Error handling: custom exception + validation response | 3h | Backend | 2026-10-18 | TODO |
| 1.4 | N+1 queries: review eager loading semua list/paginate | 3h | Backend | 2026-10-18 | TODO |

**Subtask 1.1 — Database Indexes**
- File: `database/migrations/xxxx_create_pendaftarans_table.php` (dan tabel lain)
- Tambah `.index()` untuk FK: mahasiswa_id, program_id, mitra_id, dosen_id
- Tambah `.onDelete('cascade')` explicit
- Test: `php artisan migrate:refresh --seed` harus jalan tanpa error
- Verifikasi: `php artisan migrate:status` ✅

**Subtask 1.2 — Blade Components**
- Path: `resources/views/components/`
- Komponen: `button.blade.php`, `card.blade.php`, `alert.blade.php`, `badge.blade.php`
- Gunakan Tailwind @apply atau class props
- Review: semua form/button/card di Blade refactor ke komponen
- Test: `npm run build` tanpa error, tampilan konsisten

**Subtask 1.3 — Error Handling**
- File: `app/Exceptions/Handler.php`
- Override `render()` untuk standardisasi response (JSON + HTML)
- FormRequest: tambah `failedValidation()` custom
- Test: POST invalid data → error format konsisten, 422 + pesan jelas

**Subtask 1.4 — N+1 Queries**
- Tool: `php artisan tinker` + `DB::enableQueryLog()`
- Audit: PendaftaranMahasiswaController::index, AdminRekap, dosen/pengajuan list
- Fix: `.with('relasi1.relasi2')` di semua query
- Test: query count < 5 untuk 100+ records

#### **Priority 2 (HIGH — Sprint 2 QA)**

| No. | Task | Effort | Owner | Deadline | Status |
|-----|------|--------|-------|----------|--------|
| 2.1 | Delete confirmations & loading states UI | 2h | Frontend | 2026-10-19 | TODO |
| 2.2 | Unit tests: 3-5 critical paths (status workflow, kuota) | 6h | Backend | 2026-10-19 | TODO |
| 2.3 | Accessibility audit: WCAG contrast, labels | 2h | SA | 2026-10-19 | TODO |
| 2.4 | Landing page redirect (issue #45) | 1h | Frontend | 2026-10-17 | TODO |
| 2.5 | Registrasi Mitra controller + UI (issue #44) | 3h | Frontend+Backend | 2026-10-17 | TODO |

**Subtask 2.1 — Delete Confirmation**
```js
// app.blade.php atau JS module
document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm('Yakin ingin menghapus?')) e.preventDefault();
    });
});
```

**Subtask 2.2 — Testing**
- Test file: `tests/Feature/PendaftaranWorkflowTest.php`
- Cases: (a) mahasiswa daftar → status draft, (b) pengajuan diterima mitra + kuota berkurang, (c) approval dosen → notifikasi
- Tool: Laravel Test Facade + DB::transaction()
- Run: `php artisan test` → semua hijau

**Subtask 2.3 — WCAG**
- Tool: axe DevTools browser extension
- Target: contrast ratio ≥ 4.5:1, semua input punya `<label for=>`
- Fix: update bg/text colors atau Tailwind utilities
- Test: axe scan → 0 violations (atau buat backlog untuk P3)

**Subtask 2.4 — Landing (#45)**
- Route: `Route::redirect('/', '/login');`
- Test: GET / → 302 /login
- Timeline: 1h, bisa dikerjakan hari ini

**Subtask 2.5 — Register Mitra (#44)**
- Controller: `RegisteredMitraController@create/@store`
- View: `auth/register-mitra.blade.php`
- Route: `/register-mitra` (GET/POST)
- Timeline: 3h, bisa selesai 2026-10-17

#### **Priority 3 (MEDIUM — Sprint 3)**

| No. | Task | Effort | Owner | Timeline |
|-----|------|--------|-------|----------|
| 3.1 | Caching: list program, rekap admin | 2h | Backend | Sprint 3 |
| 3.2 | Pagination: semua list (sudah ada di admin, apply ke role lain) | 2h | Frontend | Sprint 3 |
| 3.3 | Monitoring: Laravel Telescope (dev) + Sentry (prod) | 3h | DevOps | Sprint 3 |
| 3.4 | API Documentation: OpenAPI spec jika ada REST API | 2h | SA | Sprint 3 |

---

### Deliverables Analisis SA

**Dokumen yang Harus Dibuat:**

1. ✅ **Dokumen ini** — `Dokumen/Analisis-QA-SA.md` (selesai)

2. **Implementasi #45 — Landing Page** (1h)
   - PR: kecil, 1 line code
   - File: `routes/web.php`

3. **Implementasi #44 — Register Mitra** (3h)
   - PR: Controller + View + Route
   - Files: `RegisteredMitraController`, `auth/register-mitra.blade.php`, route update

4. **Action Plan Implementation** (ongoing)
   - Task 1.1-1.4 (P1) — deadline 2026-10-18
   - Task 2.1-2.5 (P2) — deadline 2026-10-19
   - Task 3.1-3.4 (P3) — Sprint 3 planning

5. **GitHub Issues Tracking**
   - Link task ke issue #43, #44, #45, #46 via commit message `Fixes #XX`
   - Update issue status saat task selesai

---

### Timeline Ringkas
- **2026-10-16 (hari ini):** Analisis selesai, mulai #45 (landing) & #44 (register mitra)
- **2026-10-17:** #45 & #44 selesai, PR siap review
- **2026-10-18:** Priority 1 (DB, components, error handling, N+1) selesai
- **2026-10-19:** Priority 2 (UI, tests, accessibility) selesai
- **2026-10-20:** Sprint 2 QA wrap, dokumentasi UAT siap

---

### Sign-Off
- **SA:** 1khsan2610
- **Approved:** Fajar1180 (PM)
- **Status:** READY TO IMPLEMENT
