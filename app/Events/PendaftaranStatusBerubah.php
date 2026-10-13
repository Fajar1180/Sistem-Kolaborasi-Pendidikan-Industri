<?php

namespace App\Events;

use App\Models\Pendaftaran;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * S1-09 — Dipicu setiap kali status pengajuan penempatan berubah.
 * Konsumsi: notifikasi (S2-07) — untuk saat ini cukup event tersedia.
 */
class PendaftaranStatusBerubah
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pendaftaran $pendaftaran,
        public string $statusBaru,
        public ?string $statusLama = null,
        public ?string $catatan = null,
    ) {}
}
