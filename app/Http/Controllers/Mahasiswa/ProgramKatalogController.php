<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * S1-08 (FR-03) — Katalog peluang program bagi mahasiswa.
 */
class ProgramKatalogController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar peluang published + pencarian (judul/mitra) + filter jenis/periode.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $jenis = (string) $request->query('jenis', '');
        if (! in_array($jenis, Program::JENIS, true)) {
            $jenis = '';
        }

        $periode = (string) $request->query('periode', '');
        if (! preg_match('/^\d{4}-\d{2}$/', $periode)) {
            $periode = '';
        }

        $programs = Program::query()
            ->published()
            ->with('mitra')
            ->withCount(['pendaftarans as kuota_terpakai' => fn ($query) => $query->whereIn('status', ['diterima_mitra', 'berjalan', 'selesai'])])
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                        ->orWhereHas('mitra', fn ($m) => $m->where('nama_perusahaan', 'like', "%{$q}%"));
                });
            })
            ->when($jenis !== '', fn ($builder) => $builder->where('jenis', $jenis))
            ->when($periode !== '', function ($builder) use ($periode) {
                [$tahun, $bulan] = array_map('intval', explode('-', $periode));
                $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfDay();
                $akhirBulan = (clone $awalBulan)->endOfMonth()->endOfDay();

                $builder->where('tanggal_mulai', '<=', $akhirBulan)
                    ->where('tanggal_selesai', '>=', $awalBulan);
            })
            ->orderBy('tanggal_mulai')
            ->paginate(9)
            ->withQueryString();

        $periodeTersedia = Program::published()
            ->orderBy('tanggal_mulai')
            ->pluck('tanggal_mulai')
            ->map(fn ($tanggal) => $tanggal->format('Y-m'))
            ->unique()
            ->values();

        return view('mahasiswa.program.index', [
            'programs' => $programs,
            'q' => $q,
            'jenisTerpilih' => $jenis,
            'periodeTerpilih' => $periode,
            'jenisTersedia' => Program::JENIS,
            'periodeTersedia' => $periodeTersedia,
        ]);
    }

    /**
     * Detail peluang + status pendaftaran mahasiswa pada program ini.
     */
    public function show(Request $request, Program $program): View
    {
        $this->authorize('view', $program);

        $program->load('mitra')
            ->loadCount(['pendaftarans as kuota_terpakai' => fn ($query) => $query->whereIn('status', ['diterima_mitra', 'berjalan', 'selesai'])]);

        $pendaftaranSaya = $request->user()->mahasiswa?->pendaftarans()
            ->where('program_id', $program->id)
            ->first();

        return view('mahasiswa.program.show', [
            'program' => $program,
            'pendaftaranSaya' => $pendaftaranSaya,
        ]);
    }
}
