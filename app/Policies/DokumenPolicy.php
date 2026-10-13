<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\User;

/**
 * S1-09 (NFR-08) — Berkas pengajuan hanya dapat diakses pihak berwenang:
 * pemilik pengajuan, dosen pembimbing terkait, mitra penyelenggara, dan admin.
 */
class DokumenPolicy
{
    public function view(User $user, Dokumen $dokumen): bool
    {
        $pendaftaran = $dokumen->pendaftaran;

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMahasiswa()) {
            return $pendaftaran->mahasiswa_id === $user->mahasiswa?->id;
        }

        if ($user->isMitra()) {
            return $pendaftaran->program?->mitra?->user_id === $user->id;
        }

        if ($user->isDosen()) {
            return $pendaftaran->dosen_pembimbing_id !== null
                && $pendaftaran->dosenPembimbing?->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Dokumen $dokumen): bool
    {
        return $user->isMahasiswa()
            && $dokumen->pendaftaran->mahasiswa_id === $user->mahasiswa?->id
            && $dokumen->pendaftaran->bisaDieditMahasiswa();
    }

    /**
     * S2-04 (FR-06) — Kelola dokumen saat pendaftaran berjalan.
     */
    public function kelola(User $user, \App\Models\Pendaftaran $pendaftaran): bool
    {
        return $user->isMahasiswa()
            && $pendaftaran->mahasiswa_id === $user->mahasiswa?->id
            && $pendaftaran->isBerjalan();
    }

    public function update(User $user, Dokumen $dokumen): bool
    {
        return $this->kelola($user, $dokumen->pendaftaran);
    }
}
