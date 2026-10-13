<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\SimpanBimbinganRequest;
use App\Models\ActivityLog;
use App\Models\Bimbingan;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * S2-05 (FR-09) — Dosen pembimbing mencatat bimbingan pada pendaftaran
 * yang dibimbingnya: riwayat catatan + tambah/edit/hapus.
 */
class BimbinganController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar mahasiswa bimbingan (pendaftaran yg ditugaskan ke dosen ini).
     */
    public function index(Request $request): View
    {
        $dosen = $request->user()->dosen;

        if (! $dosen) {
            return view('dosen.bimbingan.index', [
                'pendaftarans' => Pendaftaran::query()->whereRaw('1 = 0')->paginate(10),
                'dosen' => null,
            ]);
        }

        $pendaftarans = Pendaftaran::query()
            ->where('dosen_pembimbing_id', $dosen->id)
            ->with(['mahasiswa.user', 'program.mitra'])
            ->withCount('bimbingans')
            ->latest('diajukan_at')
            ->paginate(10);

        return view('dosen.bimbingan.index', compact('pendaftarans', 'dosen'));
    }

    /**
     * Riwayat bimbingan satu pendaftaran + ringkasan kegiatan/dokumen/laporan.
     */
    public function show(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', [Bimbingan::class, $pendaftaran]);

        $pendaftaran->load(['mahasiswa.user', 'program.mitra']);

        return view('dosen.bimbingan.show', [
            'pendaftaran' => $pendaftaran,
            'bimbingans' => $pendaftaran->bimbingans()->orderByDesc('tanggal')->get(),
            'jumlahKegiatan' => $pendaftaran->kegiatans()->count(),
            'jumlahDokumen' => $pendaftaran->dokumens()->count(),
            'laporan' => $pendaftaran->laporans()->latest()->first(),
        ]);
    }

    public function store(SimpanBimbinganRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->bimbingans()->create([
            'dosen_id' => $request->user()->dosen->id,
            'catatan' => $request->input('catatan'),
            'tanggal' => now()->toDateString(),
        ]);

        ActivityLog::catat('catat_bimbingan', $pendaftaran, 'Dosen mencatat bimbingan.');

        return redirect()->route('dosen.bimbingan.show', $pendaftaran)
            ->with('status', 'Catatan bimbingan berhasil disimpan.');
    }

    public function update(SimpanBimbinganRequest $request, Pendaftaran $pendaftaran, Bimbingan $bimbingan): RedirectResponse
    {
        abort_unless($bimbingan->pendaftaran_id === $pendaftaran->id, 404);

        $bimbingan->update(['catatan' => $request->input('catatan')]);

        ActivityLog::catat('ubah_bimbingan', $pendaftaran, 'Dosen mengubah catatan bimbingan.');

        return redirect()->route('dosen.bimbingan.show', $pendaftaran)
            ->with('status', 'Catatan bimbingan berhasil diperbarui.');
    }

    public function destroy(Request $request, Pendaftaran $pendaftaran, Bimbingan $bimbingan): RedirectResponse
    {
        $this->authorize('kelola', [Bimbingan::class, $pendaftaran]);
        abort_unless($bimbingan->pendaftaran_id === $pendaftaran->id, 404);

        $bimbingan->delete();

        ActivityLog::catat('hapus_bimbingan', $pendaftaran, 'Dosen menghapus catatan bimbingan.');

        return redirect()->route('dosen.bimbingan.show', $pendaftaran)
            ->with('status', 'Catatan bimbingan berhasil dihapus.');
    }
}
