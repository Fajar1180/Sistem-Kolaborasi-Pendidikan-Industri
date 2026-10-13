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
    public function kelolaNilai(User $user, Pendaftaran $pendaftaran, mixed ...$rest): bool
    {
        $class = $rest[0] ?? \App\Models\PenilaianDosen::class;

        return $class === \App\Models\PenilaianDosen::class
            && app(BimbinganPolicy::class)->kelolaNilai($user, $pendaftaran);
    }
}
