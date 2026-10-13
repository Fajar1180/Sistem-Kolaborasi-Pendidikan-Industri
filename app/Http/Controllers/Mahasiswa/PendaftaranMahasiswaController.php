<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S1-08 (FR-03) — Pendaftaran program oleh mahasiswa.
 */
class PendaftaranMahasiswaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Pendaftaran Saya — daftar pendaftaran milik mahasiswa.
     */
    public function index(Request $request): View
    {
        $mahasiswa = $request->user()->mahasiswa;

        $pendaftarans = $mahasiswa
            ? $mahasiswa->pendaftarans()->with('program.mitra')->latest()->get()
            : collect();

        return view('mahasiswa.pendaftaran.index', [
            'mahasiswa' => $mahasiswa,
            'pendaftarans' => $pendaftarans,
        ]);
    }

    /**
     * Halaman konfirmasi sebelum mendaftar.
     */
    public function create(Request $request, Program $program): View|RedirectResponse
    {
        $this->authorize('view', $program);

        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Profil mahasiswa Anda belum lengkap. Hubungi administrator.');
        }

        if ($alasan = $this->cekKelayakan($mahasiswa, $program)) {
            return redirect()->route('mahasiswa.program.show', $program)
                ->with('error', $alasan);
        }

        $program->load('mitra');

        return view('mahasiswa.pendaftaran.create', compact('program'));
    }

    /**
     * Simpan pendaftaran (status awal: draf — §7.5; diajukan terpisah di S1-09).
     */
    public function store(Request $request, Program $program): RedirectResponse
    {
        $this->authorize('view', $program);

        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Profil mahasiswa Anda belum lengkap. Hubungi administrator.');
        }

        if ($alasan = $this->cekKelayakan($mahasiswa, $program)) {
            return redirect()->route('mahasiswa.program.show', $program)
                ->with('error', $alasan);
        }

        try {
            $pendaftaran = DB::transaction(function () use ($mahasiswa, $program) {
                // Kunci baris program agar kuota tidak diisi bersamaan (race condition).
                Program::whereKey($program->id)->lockForUpdate()->first();

                return $mahasiswa->pendaftarans()->create([
                    'program_id' => $program->id,
                    'status' => Pendaftaran::STATUS_DRAF,
                ]);
            });
        } catch (UniqueConstraintViolationException $e) {
            // Cadangan: pendaftaran sudah ada (constraint unik mahasiswa_id+program_id).
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Anda sudah pernah mendaftar pada program ini.');
        }

        ActivityLog::catat('daftar_program', $pendaftaran, "Mendaftar program: {$program->judul}");

        return redirect()->route('mahasiswa.pengajuan.edit', $pendaftaran)
            ->with('status', 'Pendaftaran dibuat sebagai draf. Lengkapi formulir pengajuan, lalu ajukan.');
    }

    /**
     * Detail pendaftaran milik mahasiswa.
     */
    public function show(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', $pendaftaran);

        $pendaftaran->load(['program.mitra', 'dokumens']);

        return view('mahasiswa.pendaftaran.show', compact('pendaftaran'));
    }

    /**
     * Batalkan pendaftaran — hanya bila belum dikomitmenkan (diterima/berjalan/selesai).
     */
    public function destroy(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('delete', $pendaftaran);

        if (! $pendaftaran->bisaDibatalkan()) {
            return redirect()->route('mahasiswa.pendaftaran.show', $pendaftaran)
                ->with('error', 'Pendaftaran yang sudah diterima/berjalan/selesai tidak dapat dibatalkan.');
        }

        ActivityLog::catat('batal_pendaftaran', $pendaftaran, "Membatalkan pendaftaran #{$pendaftaran->id}");

        $pendaftaran->delete();

        return redirect()->route('mahasiswa.pendaftaran.index')
            ->with('status', 'Pendaftaran dibatalkan.');
    }

    /**
     * Syarat agar boleh mendaftar: program terbuka, kuota tersedia, belum pernah mendaftar.
     */
    private function cekKelayakan(Mahasiswa $mahasiswa, Program $program): ?string
    {
        if ($program->status_publikasi !== 'published') {
            return 'Program ini belum dibuka untuk pendaftaran.';
        }

        if ($program->sisaKuota() <= 0) {
            return 'Kuota program ini sudah penuh.';
        }

        if ($mahasiswa->pendaftarans()->where('program_id', $program->id)->exists()) {
            return 'Anda sudah pernah mendaftar pada program ini.';
        }

        return null;
    }
}
