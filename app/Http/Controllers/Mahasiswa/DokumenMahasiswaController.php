<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\SimpanDokumenRequest;
use App\Models\ActivityLog;
use App\Models\Dokumen;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * S2-04 (FR-06) — Kelola dokumen per pendaftaran berjalan.
 */
class DokumenMahasiswaController extends Controller
{
    use AuthorizesRequests;

    public function index(Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', $pendaftaran);

        return view('mahasiswa.dokumen.index', [
            'pendaftaran' => $pendaftaran,
            'dokumens' => $pendaftaran->dokumens()->latest()->get(),
        ]);
    }

    public function store(SimpanDokumenRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('kelola', [Dokumen::class, $pendaftaran]);

        $file = $request->file('berkas');
        $path = $file->store('dokumen/'.$pendaftaran->id, 'local');

        DB::transaction(function () use ($request, $pendaftaran, $file, $path) {
            $pendaftaran->dokumens()->create([
                'nama' => $request->input('nama') ?: $file->getClientOriginalName(),
                'path' => $path,
                'tipe' => $file->getClientMimeType(),
                'ukuran' => $file->getSize(),
            ]);
        });

        ActivityLog::catat('unggah_dokumen', $pendaftaran, 'Mengunggah dokumen: '.$file->getClientOriginalName());

        return redirect()->route('mahasiswa.dokumen.index', $pendaftaran)
            ->with('status', 'Dokumen berhasil diunggah.');
    }

    public function update(SimpanDokumenRequest $request, Pendaftaran $pendaftaran, Dokumen $dokumen): RedirectResponse
    {
        $this->authorize('update', $dokumen);

        $file = $request->file('berkas');
        $newPath = $file->store('dokumen/'.$pendaftaran->id, 'local');
        $oldPath = $dokumen->path;

        DB::transaction(function () use ($request, $dokumen, $file, $newPath) {
            $dokumen->update([
                'nama' => $request->input('nama') ?: $file->getClientOriginalName(),
                'path' => $newPath,
                'tipe' => $file->getClientMimeType(),
                'ukuran' => $file->getSize(),
            ]);
        });

        Storage::disk('local')->delete($oldPath);

        ActivityLog::catat('ganti_dokumen', $pendaftaran, 'Mengganti dokumen: '.$dokumen->nama);

        return redirect()->route('mahasiswa.dokumen.index', $pendaftaran)
            ->with('status', 'Dokumen berhasil diganti.');
    }

    public function destroy(Pendaftaran $pendaftaran, Dokumen $dokumen): RedirectResponse
    {
        $this->authorize('update', $dokumen);

        DB::transaction(function () use ($dokumen) {
            Storage::disk('local')->delete($dokumen->path);
            $dokumen->delete();
        });

        ActivityLog::catat('hapus_dokumen', $pendaftaran, 'Menghapus dokumen: '.$dokumen->nama);

        return redirect()->route('mahasiswa.dokumen.index', $pendaftaran)
            ->with('status', 'Dokumen berhasil dihapus.');
    }
}
