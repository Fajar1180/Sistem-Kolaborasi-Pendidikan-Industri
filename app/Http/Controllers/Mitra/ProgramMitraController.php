<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mitra\ProgramRequest;
use App\Models\ActivityLog;
use App\Models\Program;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * S1-07 (FR-11) — Mitra membuat, mengubah, mempublikasikan, dan menutup peluang program.
 */
class ProgramMitraController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $mitra = $request->user()->mitra;

        // Akun mitra tanpa baris profil tetap boleh membuka areanya (daftar kosong),
        // supaya tidak terhalang 403 hanya karena data profil belum lengkap.
        $programs = $mitra
            ? $mitra->programs()->withCount('pendaftarans')->latest()->get()
            : collect();

        return view('mitra.program.index', [
            'mitra' => $mitra,
            'programs' => $programs,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->mitra) {
            return $this->mintaLengkapiProfil();
        }

        return view('mitra.program.create');
    }

    public function store(ProgramRequest $request): RedirectResponse
    {
        $mitra = $request->user()->mitra;

        if (! $mitra) {
            return $this->mintaLengkapiProfil();
        }

        $program = $mitra->programs()->create(
            $request->validated() + ['status_publikasi' => Program::STATUS_DRAFT]
        );

        ActivityLog::catat('buat_peluang', $program, $program->judul);

        return redirect()->route('mitra.program.index')
            ->with('status', 'Peluang "'.$program->judul.'" dibuat sebagai draf.');
    }

    public function edit(Program $program): View
    {
        $this->authorize('update', $program);

        return view('mitra.program.edit', ['program' => $program]);
    }

    public function update(ProgramRequest $request, Program $program): RedirectResponse
    {
        $this->authorize('update', $program);

        $program->update($request->validated());

        ActivityLog::catat('ubah_peluang', $program, $program->judul);

        return redirect()->route('mitra.program.index')
            ->with('status', 'Peluang "'.$program->judul.'" diperbarui.');
    }

    public function publish(Program $program): RedirectResponse
    {
        $this->authorize('update', $program);

        $program->update(['status_publikasi' => Program::STATUS_PUBLISHED]);

        ActivityLog::catat('publikasi_peluang', $program, $program->judul);

        return back()->with('status', 'Peluang "'.$program->judul.'" dipublikasikan dan kini terlihat oleh mahasiswa.');
    }

    public function close(Program $program): RedirectResponse
    {
        $this->authorize('update', $program);

        $program->update(['status_publikasi' => Program::STATUS_CLOSED]);

        ActivityLog::catat('tutup_peluang', $program, $program->judul);

        return back()->with('status', 'Peluang "'.$program->judul.'" ditutup. Pendaftaran baru dihentikan, data lama tetap utuh.');
    }

    private function mintaLengkapiProfil(): RedirectResponse
    {
        return redirect()->route('mitra.profil.edit')
            ->with('status', 'Lengkapi profil perusahaan Anda terlebih dahulu sebelum membuat peluang.');
    }
}
