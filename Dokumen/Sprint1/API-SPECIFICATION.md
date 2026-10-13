# API Specification - Sprint 1
**Proyek:** Sistem Informasi Kolaborasi Pendidikan dan Industri  
**Sprint:** 1 (Minggu 6-7)  
**Versi:** 1.0  
**Tanggal:** 2026-10-13  

## 1. Overview
API berbasis RESTful untuk autentikasi, hak akses, data master, peluang program, pendaftaran, dan pengajuan penempatan.
- **Base URL**: `/api`
- **Format**: JSON
- **Auth**: Laravel Sanctum / JWT (bearer token) untuk endpoint yang memerlukan otentikasi
- **Validation**: FormRequest Laravel dengan aturan sesuai PRD
- **Error Response**: 
  ```json
  {
    "message": "Error description",
    "errors": {} // optional validation errors
  }
  ```

## 2. Endpoints

### 2.1 Auth (FR-01)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| POST | `/register` | Pendaftaran pengguna baru | Guest | `{name, email, password, password_confirmation, role}` | `{user, token}` |
| POST | `/login` | Masuk | Guest | `{email, password}` | `{user, token}` |
| POST | `/logout` | Keluar | Auth | - | `{message}` |
| POST | `/password/email` | Kirim link reset password | Guest | `{email}` | `{message}` |
| POST | `/password/reset` | Reset password | Guest | `{email, token, password, password_confirmation}` | `{message}` |

### 2.2 Users & Roles (FR-02, FR-14)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/users` | Daftar pengguna (with pagination, search) | Admin | - | `{data: [...]}` |
| POST | `/api/users` | Tambah pengguna baru | Admin | `{name, email, password, role, is_active}` | `{user}` |
| GET | `/api/users/{id}` | Detail pengguna | Admin / Self | - | `{user}` |
| PUT | `/api/users/{id}` | Update pengguna | Admin / Self | `{name, email, role?, is_active?}` | `{user}` |
| DELETE | `/api/users/{id}` | Nonaktifkan pengguna (soft delete) | Admin | - | `{message}` |

### 2.3 Program Master (FR-11)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/programs` | Daftar program (published only for public) | Guest/Mahasiswa | - | `{data: [...]}` |
| GET | `/api/programs/{id}` | Detail program | Guest/Mahasiswa | - | `{program}` |
| POST | `/api/programs` | Buat program baru | Mitra | `{name, period, quota, requirements, mitra_id}` | `{program}` |
| PUT | `/api/programs/{id}` | Update program | Mitra (owner) | `{name, period, quota, requirements, is_published}` | `{program}` |
| DELETE | `/api/programs/{id}` | Hapus program (soft) | Mitra (owner) | - | `{message}` |

### 2.4 Mitra (FR-11, FR-12)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/mitra/profile` | Mitra profile sendiri | Mitra | - | `{mitra}` |
| PUT | `/api/mitra/profile` | Update profile mitra | Mitra | `{name, description, contact_info}` | `{mitra}` |
| GET | `/api/mitra/opportunities` | Daftar peluang program mitra | Mitra | - | `{data: [...]}` |
| POST | `/api/mitra/opportunities` | Buat peluang program | Mitra | `{program_id, quota, start_date, end_date}` | `{opportunity}` |
| PUT | `/api/mitra/opportunities/{id}` | Update peluang | Mitra | `{quota, start_date, end_date, is_active}` | `{opportunity}` |
| DELETE | `/api/mitra/opportunities/{id}` | Hapus peluang | Mitra | - | `{message}` |

### 2.5 Pendaftaran Mahasiswa (FR-03, FR-04)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/mahasiswa/registrations` | Daftar pendaftaran mahasiswa sendiri | Mahasiswa | - | `{data: [...]}` |
| POST | `/api/mahasiswa/registrations` | Daftar program baru | Mahasiswa | `{program_id, motivation, documents[]}` | `{registration}` |
| GET | `/api/mahasiswa/registrations/{id}` | Detail pendaftaran | Mahasiswa | - | `{registration}` |
| PUT | `/api/mahasiswa/registrations/{id}` | Update pendaftaran (hanya jika masih draft) | Mahasiswa | `{motivation, documents[]}` | `{registration}` |
| DELETE | `/api/mahasiswa/registrations/{id}` | Batalkan pendaftaran | Mahasiswa | - | `{message}` |

### 2.6 Pengajuan Penempatan (FR-04)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/mahasiswa/placements` | Daftar pengajuan penempatan mahasiswa | Mahasiswa | - | `{data: [...]}` |
| POST | `/api/mahasiswa/placements` | Ajukan penempatan (setelah pendaftaran disetujui dosen) | Mahasiswa | `{registration_id, mitra_id, start_date, end_date, documents[]}` | `{placement}` |
| GET | `/api/mahasiswa/placements/{id}` | Detail pengajuan | Mahasiswa | - | `{placement}` |
| PUT | `/api/mahasiswa/placements/{id}` | Update pengajuan (jika diperlukan revisi) | Mahasiswa | `{documents[]}` | `{placement}` |

### 2.7 Dosen Approval (FR-04 via dosen)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/dosen/pending-placements` | Daftar pengajuan menunggu keputusan dosen | Dosen | - | `{data: [...]}` |
| POST | `/api/dosen/placements/{id}/approve` | Setujui pengajuan | Dosen | `{notes?}` | `{placement}` |
| POST | `/api/dosen/placements/{id}/reject` | Tolak pengajuan | Dosen | `{reason}` | `{placement}` |
| POST | `/api/dosen/placements/{id}/request-revision` | Minta revisi | Dosen | `{feedback}` | `{placement}` |

### 2.8 Mitra Selection (FR-12)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/mitra/placement-applications` | Daftar pengajuan yang diterima dosen (ready for seleksi) | Mitra | - | `{data: [...]}` |
| POST | `/api/mitra/placements/{id}/accept` | Terima peserta | Mitra | - | `{placement}` |
| POST | `/api/mitra/placements/{id}/reject` | Tolak peserta | Mitra | `{reason}` | `{placement}` |

### 2.9 Notifications & Audit (NFR-06)
| Method | Endpoint | Deskripsi | Akses | Request Body | Response |
|--------|----------|-----------|-------|--------------|----------|
| GET | `/api/notifications` | Daftar notifikasi pengguna sendiri | Auth | - | `{data: [...]}` |
| POST | `/api/notifications/{id}/read` | Tandai notifikasi sebagai sudah dibaca | Auth | - | `{message}` |
| GET | `/api/audit-logs` | Jejak aktivitas penting (admin only) | Admin | - | `{data: [...]}` |

## 3. Data Schema Contoh

### User
```json
{
  "id": 1,
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "role": "mahasiswa",
  "is_active": true,
  "email_verified_at": null,
  "created_at": "2026-10-10T08:00:00Z",
  "updated_at": "2026-10-10T08:00:00Z"
}
```

### Program
```json
{
  "id": 1,
  "name": "Magang Teknik Industri",
  "period": "Ganjil 2026/2027",
  "quota": 20,
  "requirements": "IPK >= 3.0, maksimal semester 6",
  "is_published": true,
  "mitra_id": 5,
  "created_at": "2026-10-05T00:00:00Z",
  "updated_at": "2026-10-05T00:00:00Z"
}
```

### Registration
```json
{
  "id": 1,
  "mahasiswa_id": 10,
  "program_id": 1,
  "motivation": "Saya ingin mengaplikasikan ilmu teknik produksi di industri.",
  "status": "pending", // draft, pending, approved, rejected
  "created_at": "2026-10-12T10:00:00Z",
  "updated_at": "2026-10-12T10:00:00Z"
}
```

### Placement
```json
{
  "id": 1,
  "registration_id": 1,
  "mitra_id": 5,
  "start_date": "2026-11-01",
  "end_date": "2027-04-30",
  "status": "pending_approve", // draft, pending_approve, approved_by_dosen, selected_by_mitra, rejected
  "documents": ["cv.pdf", "transkrip.pdf"],
  "created_at": "2026-10-12T14:00:00Z",
  "updated_at": "2026-10-12T14:00:00Z"
}
```

## 4. Middleware & Security
- **auth:sanctum**: Proteksi route yang memerlukan login
- **role:admin**, **role:mahasiswa**, etc.: Middleware kustom untuk role-based access
- **throttle**: Rate login attempts (max 5 attempts per 5 menit)
- **cors**: Diperbolehkan hanya dari domain aplikasi (same-origin during dev)
- **input validation**: semua input divalidasi (size, format, unique where applicable)
- **password hash**: bcrypt
- **sql injection protection**: Laravel Eloquent & query builder

## 5. Referensi
1. Laravel Sanctum Documentation: https://laravel.com/docs/sanctum
2. Laravel Form Request Validation: https://laravel.com/docs/validation#form-request-validation
3. REST API Design Guidelines: https://restfulapi.net/
4. PRD: Dokumen Perencanaan Proyek – Sistem Informasi Kolaborasi Pendidikan dan Industri (Kelompok 6).md