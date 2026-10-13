<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\KirimKomentarRequest;
use App\Models\Komentar;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * S2-07a (FR-17) — Diskusi komentar per pendaftaran: daftar + kirim.
 * Shared namespace Mahasiswa, dipakai semua peran (otorisasi via KomentarPolicy).
 */
class KomentarController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Pendaftaran $pendaftaran): View
    {
        $this->authorize('view', [Komentar::class, $pendaftaran]);

        $komentars = $pendaftaran->komentars()->with('user')->latest()->paginate(20);

        return view('komentar.index', compact('pendaftaran', 'komentars'));
    }

    public function store(KirimKomentarRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('create', [Komentar::class, $pendaftaran]);

        $komentar = $pendaftaran->komentars()->create([
            'user_id' => $request->user()->id,
            'isi' => $request->input('isi'),
        ]);

        $this->beriTahuPihakLain($pendaftaran, $komentar, $request->user());

        return back()->with('status', 'Komentar berhasil dikirim.');
    }

    /**
     * Notifikasi langsung ke semua pihak terkait kecuali penulis (admin skip).
     */
    private function beriTahuPihakLain(Pendaftaran $pendaftaran, Komentar $komentar, User $penulis): void
    {
        $pendaftaran->loadMissing(['mahasiswa.user', 'dosenPembimbing.user', 'program.mitra.user']);

        $penerima = collect([
            $pendaftaran->mahasiswa?->user,
            $pendaftaran->dosenPembimbing?->user,
            $pendaftaran->program?->mitra?->user,
        ])->filter(fn ($u) => $u instanceof User && $u->id !== $penulis->id)
            ->unique('id');

        foreach ($penerima as $user) {
            Notifikasi::kirim(
                $user,
                'Komentar baru pada pendaftaran',
                "Ada komentar baru dari {$penulis->name}.",
                route('komentar.index', $pendaftaran),
            );
        }
    }
}
