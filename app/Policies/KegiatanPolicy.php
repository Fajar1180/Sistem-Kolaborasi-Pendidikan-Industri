<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use App\Models\User;

/**
 * S2-03 FR-05 — Kegiatan: mahasiswa milik sendiri, peran lain melihat.
 */
class KegiatanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kegiatan $kegiatan): bool
    {
        $pendaftaran = $kegiatan->pendaftaran;
        return $this->owns($user, $pendaftaran) || ! $user->isMahasiswa();
    }

    public function create(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isMahasiswa()
            && $this->owns($user, $pendaftaran)
            && $pendaftaran->isBerjalan();
    }

    public function update(User $user, Kegiatan $kegiatan): bool
    {
        // Hanya boleh diedit kegiatan milik sendiri pada pendaftaran berjalan
        // dan belum ditinjau dosen (status_tinjau != 'disetujui')
        if (! $user->isMahasiswa()) {
            return false;
        }

        $pendaftaran = $kegiatan->pendaftaran;

        if (! $this->owns($user, $pendaftaran)) {
            return false;
        }

        if (! $pendaftaran->isBerjalan()) {
            return false;
        }

        // Boleh diubah sebelum ditinjau dosen (status_tinjau != 'disetujui')
        return $kegiatan->status_tinjau !== 'disetujui';
    }

    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        // Hapus: cukup owned + pendaftaran berjalan + belum ditinjau
        if (! $user->isMahasiswa()) {
            return false;
        }

        $pendaftaran = $kegiatan->pendaftaran;

        return $this->owns($user, $pendaftaran)
            && $pendaftaran->isBerjalan()
            && $kegiatan->status_tinjau !== 'disetujui';
    }

    private function owns(User $user, Pendaftaran $pendaftaran): bool
    {
        return $pendaftaran->mahasiswa_id === $user->mahasiswa?->id;
    }
}