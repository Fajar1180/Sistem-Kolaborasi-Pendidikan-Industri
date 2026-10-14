<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard disesuaikan dengan peran pengguna (FR-02).
     * Tampilkan statistik real-time per role, bukan hanya link statis.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $stats = [];

        // Statistik per role
        if ($user->isAdmin()) {
            $stats = [
                'totalUsers' => User::count(),
                'totalProgram' => Program::count(),
                'totalPendaftaran' => Pendaftaran::count(),
                'pendaftaranBerjalan' => Pendaftaran::where('status', 'berjalan')->count(),
                'menungguReview' => Pendaftaran::where('status', 'menunggu_review_dosen')->count(),
                'notifikasiBlmDibaca' => $user->notifikasis()->belumDibaca()->count(),
            ];
        } elseif ($user->isDosen()) {
            $stats = [
                'pengajuanMenunggu' => Pendaftaran::where('status', 'diajukan')->count(),
                'mahasiswaBimbingan' => $user->dosen?->pembimbingans()->count() ?? 0,
                'totalPendaftaran' => Pendaftaran::count(),
                'notifikasiBlmDibaca' => $user->notifikasis()->belumDibaca()->count(),
            ];
        } elseif ($user->isMitra()) {
            $mitra = $user->mitra;
            $stats = [
                'programAktif' => $mitra?->programs()->where('status', 'aktif')->count() ?? 0,
                'pendaftaranMasuk' => $mitra?->programs()->sum(fn($p) => $p->pendaftarans()->count()) ?? 0,
                'menungguSeleksi' => $mitra?->programs()->sum(fn($p) => $p->pendaftarans()->where('status', 'diajukan')->count()) ?? 0,
                'notifikasiBlmDibaca' => $user->notifikasis()->belumDibaca()->count(),
            ];
        } else { // Mahasiswa
            $mahasiswa = $user->mahasiswa;
            $stats = [
                'totalPendaftaran' => $mahasiswa?->pendaftarans()->count() ?? 0,
                'statusTerbaru' => $mahasiswa?->pendaftarans()->latest()->first()?->status ?? '-',
                'menungguDiajukan' => $mahasiswa?->pendaftarans()->where('status', 'draf')->count() ?? 0,
                'notifikasiBlmDibaca' => $user->notifikasis()->belumDibaca()->count(),
            ];
        }

        return view('dashboard', [
            'user' => $user,
            'roleLabel' => match ($user->role) {
                'admin' => 'Admin',
                'dosen' => 'Dosen',
                'mitra' => 'Mitra Industri',
                default => 'Mahasiswa',
            },
            'stats' => $stats,
        ]);
    }
}
