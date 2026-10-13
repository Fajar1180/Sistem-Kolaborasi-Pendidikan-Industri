<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

/**
 * Matriks hak akses PRD 7.7 — Pendaftaran: mahasiswa kelola miliknya, peran lain melihat.
 */
class PendaftaranPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        return $this->owns($user, $pendaftaran) || ! $user->isMahasiswa();
    }

    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isMahasiswa() && $this->owns($user, $pendaftaran);
    }

    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $this->update($user, $pendaftaran);
    }

    /**
     * S2-01 — Dosen pembimbing yang ditugaskan boleh memutuskan pengajuan yang
     * masih menunggu keputusan (diajukan/perlu_revisi).
     */
    public function putuskanDosen(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isDosen()
            && $pendaftaran->dosen_pembimbing_id !== null
            && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id
            && $pendaftaran->bisaDiputusDosen();
    }

    private function owns(User $user, Pendaftaran $pendaftaran): bool
    {
        return $pendaftaran->mahasiswa_id === $user->mahasiswa?->id;
    }
}
