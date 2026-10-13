<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\SimpanLaporanRequest;
use App\Models\ActivityLog;
use App\Models\Laporan;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * S2-04 (FR-07) — Satu laporan final per pendaftaran, flag terlambat otomatis.
 */
class LaporanController extends Controller
{
    use AuthorizesRequests;

    public function show(Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', $pendaftaran);

        $tenggat = $pendaftaran->program?->tanggal_selesai;
        $sisaHari = $tenggat ? (int) now()->startOfDay()->diffInDays($tenggat, false) : null;

        return view('mahasiswa.laporan.show', [
            'pendaftaran' => $pendaftaran,
            'laporan' => $pendaftaran->laporans()->latest()->first(),
            'tenggat' => $tenggat,
            'sisaHari' => $sisaHari,
        ]);
    }

    public function store(SimpanLaporanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('create', [Laporan::class, $pendaftaran]);

        $file = $request->file('berkas');
        $path = $file->store('laporan/'.$pendaftaran->id, 'local');
        $tenggat = $pendaftaran->program?->tanggal_selesai;
        $terlambat = $tenggat ? now()->startOfDay()->gt($tenggat) : false;

        DB::transaction(function () use ($pendaftaran, $path, $tenggat, $terlambat) {
            $existing = $pendaftaran->laporans()->latest()->first();

            if ($existing) {
                Storage::disk('local')->delete($existing->path);
                $existing->update([
                    'path' => $path,
                    'tenggat' => $tenggat,
                    'terlambat' => $terlambat,
                    'status_tinjau' => 'menunggu',
                ]);
            } else {
                $pendaftaran->laporans()->create([
                    'path' => $path,
                    'tenggat' => $tenggat,
                    'terlambat' => $terlambat,
                    'status_tinjau' => 'menunggu',
                ]);
            }
        });

        ActivityLog::catat('kumpul_laporan', $pendaftaran, 'Mengumpulkan laporan'.($terlambat ? ' (TERLAMBAT)' : ''));

        return redirect()->route('mahasiswa.laporan.show', $pendaftaran)
            ->with('status', $terlambat ? 'Laporan dikumpulkan — TERLAMBAT dari tenggat.' : 'Laporan berhasil dikumpulkan.');
    }

    public function destroy(Pendaftaran $pendaftaran, Laporan $laporan): RedirectResponse
    {
        $this->authorize('delete', $laporan);

        DB::transaction(function () use ($laporan) {
            Storage::disk('local')->delete($laporan->path);
            $laporan->delete();
        });

        ActivityLog::catat('hapus_laporan', $pendaftaran, 'Menghapus laporan.');

        return redirect()->route('mahasiswa.laporan.show', $pendaftaran)
            ->with('status', 'Laporan berhasil dihapus.');
    }
}
