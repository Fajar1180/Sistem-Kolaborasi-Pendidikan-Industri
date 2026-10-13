<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\Pendaftaran;
use App\Models\User;

/**
 * S2-04 (FR-07) — Laporan final milik sendiri pada pendaftaran berjalan.
 */
class LaporanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Laporan $laporan): bool
    {
        $pendaftaran = $laporan->pendaftaran;

        return $this->owns($user, $pendaftaran) || ! $user->isMahasiswa();
    }

    public function create(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isMahasiswa()
            && $this->owns($user, $pendaftaran)
            && $pendaftaran->isBerjalan();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        // Revisi boleh sebelum tenggat (penanda keterlambatan dihitung ulang).
        if (! $user->isMahasiswa()) {
            return false;
        }

        $pendaftaran = $laporan->pendaftaran;

        return $this->owns($user, $pendaftaran)
            && $pendaftaran->isBerjalan()
            && $laporan->status_tinjau !== 'disetujui';
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        if (! $user->isMahasiswa()) {
            return false;
        }

        $pendaftaran = $laporan->pendaftaran;

        return $this->owns($user, $pendaftaran)
            && $pendaftaran->isBerjalan()
            && $laporan->status_tinjau !== 'disetujui';
    }

    private function owns(User $user, Pendaftaran $pendaftaran): bool
    {
        return $pendaftaran->mahasiswa_id === $user->mahasiswa?->id;
    }
}
