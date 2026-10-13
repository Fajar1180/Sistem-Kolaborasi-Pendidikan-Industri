<?php

namespace App\Console\Commands;

use App\Events\PendaftaranStatusBerubah;
use App\Models\ActivityLog;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * S2-02 (FR-12) — Scheduler harian: pengajuan berstatus `diterima_mitra`
 * menjadi `berjalan` begitu tanggal mulai program tiba (§7.5: "Sistem, sesuai periode").
 *
 * Catatan: transisi ke `selesai` (setelah penilaian lengkap) ditangani bersama
 * S2-05/S2-06 karena bergantung pada data penilaian dosen & mitra.
 */
class MulaiProgramBerjalan extends Command
{
    protected $signature = 'program:mulai-berjalan';

    protected $description = 'Ubah pengajuan diterima_mitra menjadi berjalan bila tanggal mulai program sudah tiba';

    public function handle(): int
    {
        $jumlah = 0;

        Pendaftaran::query()
            ->where('status', Pendaftaran::STATUS_DITERIMA_MITRA)
            ->whereHas('program', fn ($q) => $q->whereDate('tanggal_mulai', '<=', now()->toDateString()))
            ->with(['program', 'mahasiswa.user'])
            ->chunkById(100, function ($daftar) use (&$jumlah) {
                foreach ($daftar as $pendaftaran) {
                    $berubah = DB::transaction(function () use ($pendaftaran) {
                        $fresh = Pendaftaran::whereKey($pendaftaran->id)->lockForUpdate()->first();

                        if ($fresh->status !== Pendaftaran::STATUS_DITERIMA_MITRA) {
                            return null;
                        }

                        $fresh->update(['status' => Pendaftaran::STATUS_BERJALAN]);

                        return $fresh;
                    });

                    if (! $berubah) {
                        continue;
                    }

                    $jumlah++;

                    ActivityLog::catat('mulai_berjalan', $berubah, "Program mulai berjalan: {$berubah->program?->judul}");

                    $user = $berubah->mahasiswa?->user;
                    if ($user) {
                        Notifikasi::kirim(
                            $user,
                            'Program mulai berjalan',
                            "Program \"{$berubah->program?->judul}\" kini berjalan. Silakan catat kegiatan Anda.",
                            route('mahasiswa.pendaftaran.show', $berubah)
                        );
                    }

                    PendaftaranStatusBerubah::dispatch(
                        $berubah,
                        Pendaftaran::STATUS_BERJALAN,
                        Pendaftaran::STATUS_DITERIMA_MITRA,
                        null
                    );
                }
            });

        $this->info("Pengajuan berubah menjadi berjalan: {$jumlah}.");

        return self::SUCCESS;
    }
}
