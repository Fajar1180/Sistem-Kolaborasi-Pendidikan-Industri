<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

/**
 * Matriks hak akses PRD 7.7 — Bimbingan & nilai: dosen pembimbing yang
 * ditugaskan mengelola miliknya; admin boleh melihat.
 */
class BimbinganPolicy
{
    /**
     * Dosen pembimbing yang ditugaskan pada pendaftaran ini.
     */
    public function kelola(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isDosen()
            && $pendaftaran->dosen_pembimbing_id !== null
            && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id;
    }

    /**
     * Lihat bimbingan: pemilik + admin.
     */
    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isAdmin() || $this->kelola($user, $pendaftaran);
    }

    /**
     * Kelola nilai: pemilik, hanya saat pendaftaran berjalan atau selesai.
     */
    public function kelolaNilai(User $user, Pendaftaran $pendaftaran): bool
    {
        return $this->kelola($user, $pendaftaran)
            && ($pendaftaran->isBerjalan() || $pendaftaran->status === Pendaftaran::STATUS_SELESAI);
    }
}
