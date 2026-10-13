<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

/**
 * S2-07a (FR-17) — Komentar diskusi pendaftaran: hanya pihak terkait
 * (mahasiswa pemilik, dosen pembimbing, mitra penyelenggara program, admin).
 */
class KomentarPolicy
{
    /**
     * Pihak terkait pendaftaran? Admin selalu ya.
     */
    public function terkait(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMahasiswa() && $pendaftaran->mahasiswa_id === $user->mahasiswa?->id) {
            return true;
        }

        if (
            $user->isDosen()
            && $pendaftaran->dosen_pembimbing_id !== null
            && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id
        ) {
            return true;
        }

        if (
            $user->isMitra()
            && $pendaftaran->program?->mitra_id !== null
            && $pendaftaran->program?->mitra_id === $user->mitra?->id
        ) {
            return true;
        }

        return false;
    }

    public function view(User $user, Pendaftaran $pendaftaran, mixed ...$rest): bool
    {
        return $this->terkait($user, $pendaftaran);
    }

    /**
     * Buat komentar: pihak terkait, saat pendaftaran berjalan atau selesai.
     */
    public function create(User $user, Pendaftaran $pendaftaran, mixed ...$rest): bool
    {
        return $this->terkait($user, $pendaftaran)
            && ($pendaftaran->isBerjalan() || $pendaftaran->status === Pendaftaran::STATUS_SELESAI);
    }
}
