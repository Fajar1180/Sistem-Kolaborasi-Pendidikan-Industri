<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

/**
 * S2-05 (FR-10) — Nilai dosen: hanya pembimbing yang ditugaskan,
 * saat pendaftaran berjalan atau selesai. Delegasi ke BimbinganPolicy
 * agar satu sumber kebenaran untuk kepemilikan.
 */
class PenilaianDosenPolicy
{
    public function kelolaNilai(User $user, Pendaftaran $pendaftaran): bool
    {
        return app(BimbinganPolicy::class)->kelolaNilai($user, $pendaftaran);
    }
}
