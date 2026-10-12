<?php

namespace App\Http\Requests\Admin;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([
                User::ROLE_MAHASISWA, User::ROLE_DOSEN, User::ROLE_MITRA, User::ROLE_ADMIN,
            ])],

            // Profil Mahasiswa
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'prodi' => $this->aturanProdi(),
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'integer', 'min:2000', 'max:2100'],
            'no_hp' => ['nullable', 'string', 'max:30'],

            // Profil Dosen
            'nidn' => ['required_if:role,dosen', 'nullable', 'string', 'max:20', 'unique:dosens,nidn'],

            // Profil Mitra
            'nama_perusahaan' => ['required_if:role,mitra', 'nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<int, mixed>
     */
    private function aturanProdi(): array
    {
        $aturan = ['required_if:role,mahasiswa,dosen', 'nullable', 'string', 'max:255'];

        if (ProgramStudi::query()->exists()) {
            $aturan[] = Rule::exists('program_studis', 'nama');
        }

        return $aturan;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'role' => 'peran',
            'nim' => 'NIM',
            'prodi' => 'program studi',
            'angkatan' => 'angkatan',
            'nidn' => 'NIDN',
            'nama_perusahaan' => 'nama perusahaan',
        ];
    }
}
