<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\User;

/**
 * Pembuatan/pembaruan profil peran otomatis saat admin mengelola pengguna (FR-14).
 */
trait MembuatProfilPengguna
{
    protected function buatProfil(User $user, array $data): void
    {
        match ($user->role) {
            User::ROLE_MAHASISWA => Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $data['nim'],
                'prodi' => $data['prodi'],
                'angkatan' => $data['angkatan'],
                'no_hp' => $data['no_hp'] ?? null,
            ]),
            User::ROLE_DOSEN => Dosen::create([
                'user_id' => $user->id,
                'nidn' => $data['nidn'],
                'prodi' => $data['prodi'],
            ]),
            User::ROLE_MITRA => Mitra::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $data['nama_perusahaan'],
                'alamat' => $data['alamat'] ?? null,
                'kontak' => $data['kontak'] ?? null,
                'deskripsi' => $data['deskripsi'] ?? null,
            ]),
            default => null,
        };
    }

    protected function perbaruiProfil(User $user, array $data): void
    {
        match ($user->role) {
            User::ROLE_MAHASISWA => $user->mahasiswa?->update([
                'nim' => $data['nim'],
                'prodi' => $data['prodi'],
                'angkatan' => $data['angkatan'],
                'no_hp' => $data['no_hp'] ?? null,
            ]),
            User::ROLE_DOSEN => $user->dosen?->update([
                'nidn' => $data['nidn'],
                'prodi' => $data['prodi'],
            ]),
            User::ROLE_MITRA => $user->mitra?->update([
                'nama_perusahaan' => $data['nama_perusahaan'],
                'alamat' => $data['alamat'] ?? null,
                'kontak' => $data['kontak'] ?? null,
                'deskripsi' => $data['deskripsi'] ?? null,
            ]),
            default => null,
        };
    }
}
