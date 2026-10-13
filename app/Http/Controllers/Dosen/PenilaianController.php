<?php

namespace App\Http\Controllers\Dosen;

use App\Events\PendaftaranStatusBerubah;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\SimpanPenilaianRequest;
use App\Models\ActivityLog;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\PenilaianDosen;
use App\Support\RubrikPenilaian;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S2-05 (FR-10) — Dosen memberi nilai per rubrik bersama, lalu finalisasi.
 * Nilai final terkunci (tidak bisa diubah). Jika nilai mitra juga final,
 * pendaftaran otomatis → selesai.
 */
class PenilaianController extends Controller
{
    use AuthorizesRequests;

    /**
     * Form rubrik: nilai per komponen + status final + nilai akhir.
     */
    public function edit(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('kelolaNilai', [PenilaianDosen::class, $pendaftaran]);

        $nilai = $pendaftaran->penilaianDosens()->get()->keyBy('komponen');

        return view('dosen.penilaian.edit', [
            'pendaftaran' => $pendaftaran->load(['mahasiswa.user', 'program.mitra']),
            'rubrik' => RubrikPenilaian::KOMPONEN,
            'nilai' => $nilai,
            'sudahFinal' => $nilai->isNotEmpty() && $nilai->every(fn ($p) => $p->is_final),
            'nilaiAkhir' => RubrikPenilaian::nilaiAkhir(
                $nilai->mapWithKeys(fn ($p) => [$p->komponen => $p->nilai])->all()
            ),
        ]);
    }

    /**
     * Simpan draf / finalisasi. Final butuh semua 4 komponen terisi.
     */
    public function update(SimpanPenilaianRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $final = (bool) $request->input('final', false);
        $data = $request->input('nilai');

        // Amankan: semua kunci rubrik harus ada (after-validator di Request sudah cek).
        abort_unless(count(array_intersect_key($data, RubrikPenilaian::KOMPONEN)) === 4, 422);

        $selesaiOtomatis = false;

        DB::transaction(function () use ($pendaftaran, $data, $final, &$selesaiOtomatis) {
            foreach ($data as $komponen => $nilai) {
                $penilaian = $pendaftaran->penilaianDosens()
                    ->firstOrNew(['komponen' => $komponen]);

                // Terkunci: baris final tidak bisa ditimpa.
                abort_if($penilaian->exists && $penilaian->is_final, 403, 'Nilai sudah final.');

                $penilaian->nilai = $nilai;
                if ($final) {
                    $penilaian->is_final = true;
                    $penilaian->difinalisasi_at = now();
                }
                $penilaian->save();
            }

            ActivityLog::catat(
                $final ? 'finalisasi_nilai' : 'simpan_nilai',
                $pendaftaran,
                $final ? 'Dosen memfinalisasi nilai.' : 'Dosen menyimpan draf nilai.'
            );

            if ($final) {
                $selesaiOtomatis = $this->cobaSelesaikan($pendaftaran);
            }
        });

        $pesan = $final
            ? ($selesaiOtomatis
                ? 'Nilai difinalisasi. Kedua nilai lengkap — pendaftaran selesai.'
                : 'Nilai difinalisasi. Menunggu nilai final dari mitra.')
            : 'Draf nilai berhasil disimpan.';

        return redirect()->route('dosen.penilaian.edit', $pendaftaran)->with('status', $pesan);
    }

    /**
     * Syarat selesai: 4 komponen dosen final + 4 komponen mitra final.
     * Dipanggil dalam transaksi. Return true jika status berubah.
     */
    private function cobaSelesaikan(Pendaftaran $pendaftaran): bool
    {
        $dosenFinal = $pendaftaran->penilaianDosens()->where('is_final', true)->count() === 4;
        $mitraFinal = $pendaftaran->penilaianMitras()->where('is_final', true)->count() === 4;

        if (! $dosenFinal || ! $mitraFinal) {
            return false;
        }

        if (! in_array($pendaftaran->status, [Pendaftaran::STATUS_BERJALAN, Pendaftaran::STATUS_SELESAI], true)) {
            return false;
        }

        if ($pendaftaran->status === Pendaftaran::STATUS_SELESAI) {
            return false;
        }

        $dari = $pendaftaran->status;
        $pendaftaran->update(['status' => Pendaftaran::STATUS_SELESAI]);

        ActivityLog::catat('pendaftaran_selesai', $pendaftaran, 'Kedua nilai final — status otomatis selesai.');

        Notifikasi::kirim(
            $pendaftaran->mahasiswa->user,
            'Program selesai',
            "Kegiatan {$pendaftaran->program->judul} dinyatakan selesai. Terima kasih!"
        );

        event(new PendaftaranStatusBerubah($pendaftaran, $dari, Pendaftaran::STATUS_SELESAI));

        return true;
    }
}
