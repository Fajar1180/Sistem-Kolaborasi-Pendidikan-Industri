<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\PenilaianMitra;
use App\Models\User;

/**
 * S2-06 (FR-11) — Mitra penyelenggara program menilai peserta berjalan
 * di programnya. Satu sumber kebenaran untuk otorisasi nilai mitra,
 * mirror BimbinganPolicy di sisi dosen (S2-05).
 */
class PenilaianMitraPolicy
{
    /**
     * Dipanggil via $this->authorize('kelolaNilai', [PenilaianMitra::class, $pendaftaran]).
     * Gate unwrap array → (user, class-string, pendaftaran); variadic menampung
     * class-string agar signature tahan terhadap cara Gate menyebar argumen.
     */
    public function kelolaNilai(User $user, Pendaftaran $pendaftaran, mixed ...$rest): bool
    {
        $class = $rest[0] ?? PenilaianMitra::class;

        return $class === PenilaianMitra::class
            && $user->isMitra()
            && $pendaftaran->program?->mitra_id !== null
            && $pendaftaran->program?->mitra_id === $user->mitra?->id;
    }
}
