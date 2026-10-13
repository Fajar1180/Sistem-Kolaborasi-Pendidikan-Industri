<?php

namespace App\Listeners;

use App\Events\PendaftaranStatusBerubah;
use App\Mail\StatusPendaftaranMail;
use App\Models\Pendaftaran;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * S2-07a — Email untuk status penting saja (disetujui/ditolak/diterima/selesai).
 * Fail-safe: try/catch agar kegagalan kirim tak ganggu alur utama.
 */
class KirimEmailStatusPenting implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public const STATUS_PENTING = [
        Pendaftaran::STATUS_DISETUJUI_DOSEN,
        Pendaftaran::STATUS_DITOLAK,
        Pendaftaran::STATUS_DITERIMA_MITRA,
        Pendaftaran::STATUS_SELESAI,
    ];

    public function handle(PendaftaranStatusBerubah $event): void
    {
        if (! in_array($event->statusBaru, self::STATUS_PENTING, true)) {
            return;
        }

        try {
            $pendaftaran = $event->pendaftaran;
            $pendaftaran->loadMissing('mahasiswa.user', 'program');

            $email = $pendaftaran->mahasiswa?->user?->email;

            if (! $email) {
                return;
            }

            Mail::to($email)->send(new StatusPendaftaranMail(
                $pendaftaran,
                $event->statusBaru,
                $event->catatan,
            ));
        } catch (Throwable $e) {
            // ponytail: telan error agar listener gagal tak ganggu transaksi;
            // naikkan lagi hanya bila ingin manfaatkan queue retry (tries=3).
            Log::warning('KirimEmailStatusPenting gagal: '.$e->getMessage(), [
                'pendaftaran_id' => $event->pendaftaran->id ?? null,
                'status' => $event->statusBaru,
            ]);
        }
    }
}
