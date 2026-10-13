<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\SimpanKegiatanRequest;
use App\Models\ActivityLog;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    use AuthorizesRequests;

    public function index(Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', $pendaftaran);

        return view('mahasiswa.kegiatan.index', [
            'pendaftaran' => $pendaftaran,
            'kegiatans' => $pendaftaran->kegiatans()->orderByDesc('tanggal')->get(),
        ]);
    }

    public function create(Pendaftaran $pendaftaran): View
    {
        $this->authorize('create', [Kegiatan::class, $pendaftaran]);

        return view('mahasiswa.kegiatan.create', ['pendaftaran' => $pendaftaran]);
    }

    public function store(SimpanKegiatanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('create', [Kegiatan::class, $pendaftaran]);

        DB::transaction(function () use ($request, $pendaftaran) {
            $pendaftaran->kegiatans()->create([
                'tanggal' => $request->input('tanggal'),
                'uraian' => $request->input('uraian'),
                'bukti_path' => $request->hasFile('bukti')
                    ? $request->file('bukti')->store('kegiatan/'.$pendaftaran->id, 'local')
                    : null,
                'status_tinjau' => 'menunggu',
            ]);
        });

        ActivityLog::catat('buat_kegiatan', $pendaftaran, 'Membuat kegiatan: ' . $request->input('uraian'));

        return redirect()->route('mahasiswa.kegiatan.index', $pendaftaran)
            ->with('status', 'Kegiatan berhasil dicatat.');
    }

    public function edit(Pendaftaran $pendaftaran, Kegiatan $kegiatan): View
    {
        $this->authorize('update', $kegiatan);

        return view('mahasiswa.kegiatan.edit', [
            'pendaftaran' => $pendaftaran,
            'kegiatan' => $kegiatan,
        ]);
    }

    public function update(SimpanKegiatanRequest $request, Pendaftaran $pendaftaran, Kegiatan $kegiatan): RedirectResponse
    {
        $this->authorize('update', $kegiatan);

        DB::transaction(function () use ($request, $pendaftaran, $kegiatan) {
            $oldBukti = $kegiatan->bukti_path;

            if ($request->hasFile('bukti')) {
                $newBuktiPath = $request->file('bukti')->store('kegiatan/'.$pendaftaran->id, 'local');
                Storage::disk('local')->delete($oldBukti);
                $kegiatan->bukti_path = $newBuktiPath;
            }

            $kegiatan->update([
                'tanggal' => $request->input('tanggal'),
                'uraian' => $request->input('uraian'),
            ]);
        });

        ActivityLog::catat('ubah_kegiatan', $pendaftaran, 'Mengubah kegiatan: ' . $request->input('uraian'));

        return redirect()->route('mahasiswa.kegiatan.index', $pendaftaran)
            ->with('status', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran, Kegiatan $kegiatan): RedirectResponse
    {
        $this->authorize('delete', $kegiatan);

        DB::transaction(function () use ($kegiatan) {
            if ($kegiatan->bukti_path) {
                Storage::disk('local')->delete($kegiatan->bukti_path);
            }
            $kegiatan->delete();
        });

        ActivityLog::catat('hapus_kegiatan', $pendaftaran, 'Menghapus kegiatan: ' . $kegiatan->uraian);

        return redirect()->route('mahasiswa.kegiatan.index', $pendaftaran)
            ->with('status', 'Kegiatan berhasil dihapus.');
    }
}