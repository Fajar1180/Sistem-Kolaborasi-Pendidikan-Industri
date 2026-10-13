<?php

namespace App\Http\Controllers\Mitra;

use App\Events\PendaftaranStatusBerubah;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mitra\SimpanPenilaianMitraRequest;
use App\Models\ActivityLog;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\PenilaianMitra;
use App\Support\RubrikPenilaian;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S2-06 (FR-11) — Mitra memberi nilai per rubrik bersama, lalu finalisasi.
 * Mirror PenilaianController dosen (S2-05): nilai final terkunci; jika nilai
 * dosen juga final, pendaftaran otomatis → selesai.
 */
class PenilaianMitraController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar peserta berjalan di program milik mitra.
     */
    public function index(Request $request): View
    {
        $mitraId = $request->user()->mitra?->id;

        $pesertas = Pendaftaran::where('status', Pendaftaran::STATUS_BERJALAN)
            ->whereHas('program', fn ($q) => $q->where('mitra_id', $mitraId))
            ->with(['mahasiswa.user', 'program'])
            ->withCount(['penilaianMitras as penilaian_final_count' => fn ($q) => $q->where('is_final', true)])
            ->paginate(15);

        return view('mitra.penilaian.index', ['pesertas' => $pesertas]);
    }

    /**
     * Form rubrik: nilai per komponen + status final + nilai akhir.
     */
    public function edit(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('kelolaNilai', [PenilaianMitra::class, $pendaftaran]);

        $nilai = $pendaftaran->penilaianMitras()->get()->keyBy('komponen');

        return view('mitra.penilaian.edit', [
            'pendaftaran' => $pendaftaran->load(['mahasiswa.user', 'program']),
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
    public function update(SimpanPenilaianMitraRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $final = (bool) $request->input('final', false);
        $data = $request->input('nilai');

        abort_unless(count(array_intersect_key($data, RubrikPenilaian::KOMPONEN)) === 4, 422);

        $selesaiOtomatis = false;

        DB::transaction(function () use ($pendaftaran, $data, $final, &$selesaiOtomatis) {
            foreach ($data as $komponen => $nilai) {
                $penilaian = $pendaftaran->penilaianMitras()
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
                $final ? 'Mitra memfinalisasi nilai.' : 'Mitra menyimpan draf nilai.'
            );

            if ($final) {
                $selesaiOtomatis = $this->cobaSelesaikan($pendaftaran);
            }
        });

        $pesan = $final
            ? ($selesaiOtomatis
                ? 'Nilai difinalisasi. Kedua nilai lengkap — pendaftaran selesai.'
                : 'Nilai difinalisasi. Menunggu nilai final dari dosen.')
            : 'Draf nilai berhasil disimpan.';

        return redirect()->route('mitra.penilaian.edit', $pendaftaran)->with('status', $pesan);
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

        if ($pendaftaran->status === Pendaftaran::STATUS_SELESAI) {
            return false;
        }

        if (! in_array($pendaftaran->status, [Pendaftaran::STATUS_BERJALAN, Pendaftaran::STATUS_SELESAI], true)) {
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
