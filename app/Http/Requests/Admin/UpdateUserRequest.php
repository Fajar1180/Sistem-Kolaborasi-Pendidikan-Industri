<?php

namespace App\Http\Requests\Admin;

use App\Models\ProgramStudi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');
        $peran = $user->role;

        $aturan = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($peran === 'mahasiswa') {
            $aturan['nim'] = ['required', 'string', 'max:20', Rule::unique('mahasiswas', 'nim')->ignore($user->mahasiswa?->id)];
            $aturan['prodi'] = $this->aturanProdi();
            $aturan['angkatan'] = ['required', 'integer', 'min:2000', 'max:2100'];
            $aturan['no_hp'] = ['nullable', 'string', 'max:30'];
        }

        if ($peran === 'dosen') {
            $aturan['nidn'] = ['required', 'string', 'max:20', Rule::unique('dosens', 'nidn')->ignore($user->dosen?->id)];
            $aturan['prodi'] = $this->aturanProdi();
        }

        if ($peran === 'mitra') {
            $aturan['nama_perusahaan'] = ['required', 'string', 'max:255'];
            $aturan['alamat'] = ['nullable', 'string', 'max:255'];
            $aturan['kontak'] = ['nullable', 'string', 'max:50'];
            $aturan['deskripsi'] = ['nullable', 'string'];
        }

        return $aturan;
    }

    /**
     * @return array<int, mixed>
     */
    private function aturanProdi(): array
    {
        $aturan = ['required', 'string', 'max:255'];

        if (ProgramStudi::query()->exists()) {
            $aturan[] = Rule::exists('program_studis', 'nama');
        }

        return $aturan;
    }
}
