<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Events\PendaftaranStatusBerubah;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\SimpanPengajuanRequest;
use App\Models\ActivityLog;
use App\Models\Dosen;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * S1-09 (FR-04) — Pengajuan penempatan:
 * isi formulir (catatan + lampiran berkas) lalu ajukan ke dosen pembimbing.
 */
class PengajuanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Form pengajuan. Status draf/perlu_revisi dapat diubah; lainnya hanya-baca.
     */
    public function edit(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('update', $pendaftaran);

        $pendaftaran->load(['program.mitra', 'dokumens']);

        return view('mahasiswa.pengajuan.edit', [
            'pendaftaran' => $pendaftaran,
            'bisaDiedit' => $pendaftaran->bisaDieditMahasiswa(),
        ]);
    }

    /**
     * Simpan draf pengajuan: catatan + unggah/hapus berkas.
     */
    public function update(SimpanPengajuanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('update', $pendaftaran);

        if (! $pendaftaran->bisaDieditMahasiswa()) {
            return redirect()->route('mahasiswa.pendaftaran.show', $pendaftaran)
                ->with('error', 'Pengajuan yang sudah diajukan tidak dapat diubah.');
        }

        DB::transaction(function () use ($request, $pendaftaran) {
            // Hapus berkas yang dipilih (hanya milik pendaftaran ini).
            $ids = $request->input('hapus_dokumen', []);
            if (! empty($ids)) {
                $dokumens = $pendaftaran->dokumens()->whereIn('id', $ids)->get();
                foreach ($dokumens as $dokumen) {
                    Storage::disk('local')->delete($dokumen->path);
                    $dokumen->delete();
                }
            }

            // Unggah berkas baru ke storage privat (NFR-08).
            foreach ($request->file('files', []) as $file) {
                $path = $file->store('dokumen-pengajuan/'.$pendaftaran->id, 'local');
                $pendaftaran->dokumens()->create([
                    'nama' => $file->getClientOriginalName(),
                    'path' => $path,
                    'tipe' => strtolower($file->getClientOriginalExtension()),
                    'ukuran' => $file->getSize(),
                ]);
            }

            $pendaftaran->update([
                'catatan_mahasiswa' => $request->input('catatan_mahasiswa'),
            ]);
        });

        ActivityLog::catat('simpan_pengajuan', $pendaftaran, 'Menyimpan draf pengajuan penempatan.');

        return redirect()->route('mahasiswa.pengajuan.edit', $pendaftaran)
            ->with('status', 'Draf pengajuan tersimpan.');
    }

    /**
     * Ajukan penempatan: draf/perlu_revisi -> diajukan (terkunci dari mahasiswa).
     */
    public function ajukan(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('update', $pendaftaran);

        if (! $pendaftaran->bisaDieditMahasiswa()) {
            return redirect()->route('mahasiswa.pendaftaran.show', $pendaftaran)
                ->with('error', 'Pengajuan ini sudah diajukan.');
        }

        $statusLama = $pendaftaran->status;

        $pendaftaran->update([
            'status' => Pendaftaran::STATUS_DIAJUKAN,
            'diajukan_at' => now(),
        ]);

        // S2-01 — Tugaskan dosen pembimbing (utamakan prodi sama, beban paling sedikit).
        if (! $pendaftaran->dosen_pembimbing_id) {
            if ($dosen = Dosen::pilihPembimbing($pendaftaran->mahasiswa?->prodi)) {
                $pendaftaran->update(['dosen_pembimbing_id' => $dosen->id]);
            }
        }

        ActivityLog::catat('ajukan_pendaftaran', $pendaftaran, "Mengajukan penempatan: {$pendaftaran->program?->judul}");
        PendaftaranStatusBerubah::dispatch($pendaftaran, Pendaftaran::STATUS_DIAJUKAN, $statusLama);

        return redirect()->route('mahasiswa.pendaftaran.show', $pendaftaran)
            ->with('status', 'Pengajuan berhasil diajukan. Menunggu keputusan dosen pembimbing.');
    }
}
