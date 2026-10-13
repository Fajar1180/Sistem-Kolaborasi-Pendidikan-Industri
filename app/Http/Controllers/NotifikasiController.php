<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * S2-07b (FR-17, Issue #18) — Arsip notifikasi milik pengguna + penanda baca.
 */
class NotifikasiController extends Controller
{
    /** Arsip notifikasi milik auth user, terbaru dulu. */
    public function index(Request $request): View
    {
        $notifikasis = $request->user()->notifikasis()
            ->latest()
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasis'));
    }

    /** Tandai satu notifikasi sebagai dibaca (hanya pemilik). */
    public function tandaiDibaca(Request $request, Notifikasi $notifikasi): RedirectResponse
    {
        abort_unless($notifikasi->user_id === $request->user()->id, 403);

        if (! $notifikasi->dibaca_at) {
            $notifikasi->update(['dibaca_at' => now()]);
        }

        return redirect($notifikasi->link ?: route('notifikasi.index'));
    }

    /** Tandai semua notifikasi milik user sebagai dibaca. */
    public function tandaiSemua(Request $request): RedirectResponse
    {
        $request->user()->notifikasis()->belumDibaca()->update(['dibaca_at' => now()]);

        return redirect()->route('notifikasi.index')->with('status', 'Semua notifikasi ditandai dibaca.');
    }
}
