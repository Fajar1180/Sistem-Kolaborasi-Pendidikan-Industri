<?php

namespace App\Http\Controllers\Mitra;

use App\Events\PendaftaranStatusBerubah;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mitra\KeputusanMitraRequest;
use App\Models\ActivityLog;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * S2-02 (FR-12) — Mitra menyeleksi pelamar yang sudah disetujui dosen:
 * terima (→ diterima_mitra, dibatasi kuota) atau tolak wajib alasan (→ ditolak).
 * Setiap keputusan tercatat (NFR-06) dan memicu notifikasi ke mahasiswa.
 */
class SeleksiMitraController extends Controller
{
    use AuthorizesRequests;

    /**
     * Antrean pelamar sebuah program milik mitra.
     * Hanya pengajuan berstatus `disetujui_dosen` yang ditampilkan.
     */
    public function index(Request $request, Program $program): View
    {
        $this->authorize('update', $program);

        $pelamars = $program->pendaftarans()
            ->where('status', Pendaftaran::STATUS_DISETUJUI_DOSEN)
            ->with(['mahasiswa.user'])
            ->oldest('disetujui_dosen_at')
            ->paginate(15);

        return view('mitra.pelamar.index', [
            'program' => $program,
            'pelamars' => $pelamars,
            'sisaKuota' => $program->sisaKuota(),
        ]);
    }

    /**
     * Terima pelamar → diterima_mitra. Dibatasi kuota secara transaksional.
     */
    public function terima(Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('seleksiMitra', $pendaftaran);

        $hasil = DB::transaction(function () use ($pendaftaran) {
            $fresh = Pendaftaran::whereKey($pendaftaran->id)->lockForUpdate()->first();

            if (! $fresh->bisaDiseleksiMitra()) {
                return 'sudah'; // idempoten: sudah diputuskan sebelumnya.
            }

            // Kunci baris program agar penerimaan paralel tidak melewati kuota.
            $program = Program::whereKey($fresh->program_id)->lockForUpdate()->first();

            if ($program->kuotaPenuh()) {
                return 'kuota';
            }

            $fresh->update([
                'status' => Pendaftaran::STATUS_DITERIMA_MITRA,
                'diterima_mitra_at' => now(),
                'catatan' => null,
            ]);

            return 'ok';
        });

        if ($hasil === 'kuota') {
            return back()->with('error', 'Kuota program sudah penuh — pelamar ini belum dapat diterima.');
        }

        if ($hasil === 'sudah') {
            return back()->with('error', 'Pelamar ini sudah diputuskan sebelumnya.');
        }

        $pendaftaran->refresh();
        ActivityLog::catat('terima_peserta', $pendaftaran, "Terima peserta: {$pendaftaran->mahasiswa?->user?->name} — {$pendaftaran->program?->judul}");
        $this->beriTahuMahasiswa($pendaftaran, 'diterima');

        return back()->with('status', 'Peserta diterima. Status berubah menjadi "Diterima Mitra".');
    }

    /**
     * Tolak pelamar (wajib alasan) → ditolak.
     */
    public function tolak(KeputusanMitraRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorize('seleksiMitra', $pendaftaran);

        $hasil = DB::transaction(function () use ($request, $pendaftaran) {
            $fresh = Pendaftaran::whereKey($pendaftaran->id)->lockForUpdate()->first();

            if (! $fresh->bisaDiseleksiMitra()) {
                return null;
            }

            $fresh->update([
                'status' => Pendaftaran::STATUS_DITOLAK,
                'catatan' => $request->input('catatan'),
            ]);

            return $fresh;
        });

        if ($hasil === null) {
            return back()->with('error', 'Pelamar ini sudah diputuskan sebelumnya.');
        }

        ActivityLog::catat('tolak_peserta', $hasil, "Tolak peserta: {$hasil->mahasiswa?->user?->name} — {$hasil->program?->judul}");
        $this->beriTahuMahasiswa($hasil, 'ditolak', $request->input('catatan'));

        return back()->with('status', 'Pelamar ditolak.');
    }

    /**
     * Notifikasi dalam sistem ke mahasiswa (S2-07 menyempurnakan UI/email)
     * dan pemicu event perubahan status.
     */
    private function beriTahuMahasiswa(Pendaftaran $pendaftaran, string $keputusan, ?string $catatan = null): void
    {
        $user = $pendaftaran->mahasiswa?->user;
        if (! $user) {
            return;
        }

        $judul = $keputusan === 'diterima' ? 'Diterima mitra' : 'Ditolak mitra';
        $isi = "Pengajuan penempatan Anda pada program \"{$pendaftaran->program?->judul}\" berstatus: {$pendaftaran->labelStatus()}.";
        if ($catatan) {
            $isi .= " Catatan: {$catatan}";
        }

        Notifikasi::kirim($user, $judul, $isi, route('mahasiswa.pendaftaran.show', $pendaftaran));

        PendaftaranStatusBerubah::dispatch(
            $pendaftaran,
            $pendaftaran->status,
            Pendaftaran::STATUS_DISETUJUI_DOSEN,
            $catatan
        );
    }
}
