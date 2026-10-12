<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;

/**
 * Matriks hak akses PRD 7.7 — Peluang program: mitra kelola miliknya, peran lain melihat.
 */
class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Program $program): bool
    {
        if ($user->isMitra()) {
            return $program->status_publikasi === 'published' || $this->owns($user, $program);
        }

        return $program->status_publikasi === 'published';
    }

    public function create(User $user): bool
    {
        return $user->isMitra();
    }

    public function update(User $user, Program $program): bool
    {
        return $user->isMitra() && $this->owns($user, $program);
    }

    public function delete(User $user, Program $program): bool
    {
        return $this->update($user, $program);
    }

    private function owns(User $user, Program $program): bool
    {
        return $program->mitra_id === $user->mitra?->id;
    }
}
