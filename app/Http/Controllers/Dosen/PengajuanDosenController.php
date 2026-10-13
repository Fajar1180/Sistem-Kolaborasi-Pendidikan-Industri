<?php

namespace App\Http\Controllers\Dosen;

use App\Events\PendaftaranStatusBerubah;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\KeputusanPengajuanRequest;
use App\Models\ActivityLog;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S2-01 (FR-08) — Dosen pembimbing memutuskan pengajuan penempatan:
 * setujui (→ disetujui_dosen, diteruskan ke mitra), tolak (→ ditolak),
 * atau minta revisi (→ perlu_revisi). Semua tercatat (NFR-06) dan memicu
 * notifikasi ke mahasiswa.
 *
 * Controller ini juga melayani admin secara hanya-baca (matriks §7.7: admin
 * melihat semua, tanpa hak memutuskan).
 */
class PengajuanDosenController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar pengajuan penempatan. Dosen: hanya yang ditugaskan kepadanya;
     * admin: seluruh pengajuan. Dilengkapi filter status.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $dosen = $user->dosen;
        $filter = (string) $request->query('filter', $user->isDosen() ? 'menunggu' : 'semua');

        // Dosen tanpa profil: tampilkan kosong (degradasi anggun, konsisten S1-07/08).
        if ($user->isDosen() && ! $dosen) {
            return view('dosen.pengajuan.index', [
                'pendaftarans' => Pendaftaran::query()->whereRaw('1 = 0')->paginate(10),
                'filter' => $filter,
                'jumlah' => ['menunggu' => 0, 'perlu_revisi' => 0],
                'dosen' => null,
            ]);
        }

        $scope = fn () => Pendaftaran::query()
            ->when($user->isDosen(), fn ($q) => $q->where('dosen_pembimbing_id', $dosen->id));

        $jumlah = [
            'menunggu' => $scope()->where('status', Pendaftaran::STATUS_DIAJUKAN)->count(),
            'perlu_revisi' => $scope()->where('status', Pendaftaran::STATUS_PERLU_REVISI)->count(),
        ];

        $query = $scope()->with(['mahasiswa.user', 'program.mitra'])->latest('diajukan_at');

        match ($filter) {
            'perlu_revisi' => $query->where('status', Pendaftaran::STATUS_PERLU_REVISI),
            'riwayat' => $query->whereIn('status', [
                Pendaftaran::STATUS_DISETUJUI_DOSEN, Pendaftaran::STATUS_DITOLAK,
            ]),
            'semua' => null,
            default => $query->where('status', Pendaftaran::STATUS_DIAJUKAN),
        };

        $pendaftarans = $query->paginate(10)->withQueryString();

        return view('dosen.pengajuan.index', compact('pendaftarans', 'filter', 'jumlah', 'dosen'));
    }

    /**
     * Detail pengajuan: data mahasiswa, program, dan berkas unduhan.
     */
    public function show(Request $request, Pendaftaran $pendaftaran): View
    {
        $user = $request->user();

        if ($user->isDosen()) {
            abort_unless(
                $pendaftaran->dosen_pembimbing_id === $user->dosen?->id,
                403,
                'Pengajuan ini bukan untuk Anda.'
            );
        }

        $pendaftaran->load(['mahasiswa.user', 'program.mitra', 'dokumens']);

        $bolehMemutuskan = $user->isDosen()
            && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id
            && $pendaftaran->bisaDiputusDosen();

        return view('dosen.pengajuan.show', compact('pendaftaran', 'bolehMemutuskan'));
    }

    /**
     * Setujui pengajuan → disetujui_dosen (diteruskan ke antrean seleksi mitra).
     */
    public function setujui(KeputusanPengajuanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        return $this->putuskan($request, $pendaftaran, Pendaftaran::STATUS_DISETUJUI_DOSEN);
    }

    /**
     * Tolak pengajuan (wajib alasan) → ditolak.
     */
    public function tolak(KeputusanPengajuanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        return $this->putuskan($request, $pendaftaran, Pendaftaran::STATUS_DITOLAK);
    }

    /**
     * Minta revisi (wajib catatan) → perlu_revisi.
     */
    public function revisi(KeputusanPengajuanRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        return $this->putuskan($request, $pendaftaran, Pendaftaran::STATUS_PERLU_REVISI);
    }

    /**
     * Terapkan keputusan secara idempoten: aksi ganda (refresh/back-button atau
     * dua pengaju) tidak mengubah status dua kali karena transisi hanya sah dari
     * status yang masih menunggu keputusan dosen.
     */
    private function putuskan(KeputusanPengajuanRequest $request, Pendaftaran $pendaftaran, string $statusBaru): RedirectResponse
    {
        $this->pastikanMilikDosen($request, $pendaftaran);

        $hasil = DB::transaction(function () use ($request, $pendaftaran, $statusBaru) {
            $fresh = Pendaftaran::whereKey($pendaftaran->id)->lockForUpdate()->first();

            if (! $fresh->bisaDiputusDosen()) {
                return null; // sudah diputuskan — jangan ubah dua kali.
            }

            $statusLama = $fresh->status;
            $catatan = $request->input('catatan');

            $atribut = ['status' => $statusBaru, 'catatan' => $catatan];
            if ($statusBaru === Pendaftaran::STATUS_DISETUJUI_DOSEN) {
                $atribut['disetujui_dosen_at'] = now();
            }

            $fresh->update($atribut);

            return [$fresh, $statusLama, $catatan];
        });

        if ($hasil === null) {
            return redirect()->route('dosen.pengajuan.show', $pendaftaran)
                ->with('error', 'Pengajuan ini sudah diputuskan sebelumnya.');
        }

        [$fresh, $statusLama, $catatan] = $hasil;

        $aksi = match ($statusBaru) {
            Pendaftaran::STATUS_DISETUJUI_DOSEN => 'setujui_pengajuan',
            Pendaftaran::STATUS_DITOLAK => 'tolak_pengajuan',
            default => 'minta_revisi_pengajuan',
        };
        ActivityLog::catat($aksi, $fresh, "Keputusan dosen: {$fresh->labelStatus()} — {$fresh->program?->judul}");

        $this->beriTahuMahasiswa($fresh, $statusBaru, $catatan);

        PendaftaranStatusBerubah::dispatch($fresh, $statusBaru, $statusLama, $catatan);

        $pesan = match ($statusBaru) {
            Pendaftaran::STATUS_DISETUJUI_DOSEN => 'Pengajuan disetujui dan diteruskan ke mitra.',
            Pendaftaran::STATUS_DITOLAK => 'Pengajuan ditolak.',
            default => 'Permintaan revisi dikirim ke mahasiswa.',
        };

        return redirect()->route('dosen.pengajuan.index')->with('status', $pesan);
    }

    /**
     * Hanya dosen pembimbing yang ditugaskan boleh memutuskan (matriks §7.7).
     */
    private function pastikanMilikDosen(Request $request, Pendaftaran $pendaftaran): void
    {
        $user = $request->user();

        abort_unless(
            $user->isDosen() && $pendaftaran->dosen_pembimbing_id === $user->dosen?->id,
            403,
            'Hanya dosen pembimbing yang ditugaskan dapat memutuskan pengajuan ini.'
        );
    }

    /**
     * Notifikasi dalam sistem ke mahasiswa (S2-07 menyempurnakan UI/email).
     */
    private function beriTahuMahasiswa(Pendaftaran $pendaftaran, string $statusBaru, ?string $catatan): void
    {
        $user = $pendaftaran->mahasiswa?->user;
        if (! $user) {
            return;
        }

        $judul = match ($statusBaru) {
            Pendaftaran::STATUS_DISETUJUI_DOSEN => 'Pengajuan disetujui dosen',
            Pendaftaran::STATUS_DITOLAK => 'Pengajuan ditolak',
            default => 'Pengajuan perlu revisi',
        };

        $isi = "Pengajuan penempatan Anda pada program \"{$pendaftaran->program?->judul}\" berstatus: {$pendaftaran->labelStatus()}.";
        if ($catatan) {
            $isi .= " Catatan: {$catatan}";
        }

        Notifikasi::kirim($user, $judul, $isi, route('mahasiswa.pendaftaran.show', $pendaftaran));
    }
}
