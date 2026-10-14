<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\ProgramStudi;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * S2-08 (FR-15, FR-16) — Admin: pengawasan program, rekapitulasi, dan ekspor laporan.
 */
class RekapController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pendaftaran::query()
            ->with(['mahasiswa.user', 'program.mitra', 'dosenPembimbing.user']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Filter prodi (kolom string pada mahasiswas)
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', fn($q) =>
                $q->where('prodi', $request->string('prodi'))
            );
        }

        // Filter mitra
        if ($request->filled('mitra')) {
            $query->whereHas('program', fn($q) => 
                $q->where('mitra_id', $request->integer('mitra'))
            );
        }

        // Filter periode (tanggal mulai program)
        if ($request->filled('periode')) {
            $query->whereHas('program', fn($q) => 
                $q->whereYear('tanggal_mulai', $request->integer('periode'))
            );
        }

        $pendaftarans = $query->latest()->paginate(20)->withQueryString();

        // Statistik ringkas
        $stats = [
            'total' => Pendaftaran::count(),
            'berjalan' => Pendaftaran::where('status', Pendaftaran::STATUS_BERJALAN)->count(),
            'selesai' => Pendaftaran::where('status', Pendaftaran::STATUS_SELESAI)->count(),
            'diajukan' => Pendaftaran::where('status', Pendaftaran::STATUS_DIAJUKAN)->count(),
        ];

        return view('admin.rekap.index', [
            'pendaftarans' => $pendaftarans,
            'stats' => $stats,
            'filter' => $request->only(['status', 'prodi', 'mitra', 'periode']),
            'prodis' => Mahasiswa::query()->select('prodi')->distinct()->orderBy('prodi')->pluck('prodi'),
            'mitras' => Mitra::orderBy('nama_perusahaan')->get(),
        ]);
    }

    public function perProgram(): View
    {
        $programs = Program::query()
            ->with('mitra')
            ->withCount('pendaftarans')
            ->get()
            ->map(function ($prog) {
                $pendaftarans = $prog->pendaftarans;
                return [
                    'program' => $prog,
                    'total_peserta' => $pendaftarans->count(),
                    'diterima' => $pendaftarans->where('status', Pendaftaran::STATUS_DITERIMA_MITRA)->count(),
                    'berjalan' => $pendaftarans->where('status', Pendaftaran::STATUS_BERJALAN)->count(),
                    'selesai' => $pendaftarans->where('status', Pendaftaran::STATUS_SELESAI)->count(),
                ];
            });

        return view('admin.rekap.per-program', compact('programs'));
    }

    public function perProdi(): View
    {
        $groups = Mahasiswa::query()
            ->select('prodi', \DB::raw('COUNT(*) as total'))
            ->groupBy('prodi')
            ->orderBy('prodi')
            ->get();

        $prodis = $groups->map(function ($g) {
            $pendaftarans = Pendaftaran::whereHas('mahasiswa', fn($q) =>
                $q->where('prodi', $g->prodi))->get();

            return [
                'prodi' => $g->prodi,
                'total_pendaftaran' => $pendaftarans->count(),
                'berjalan' => $pendaftarans->where('status', Pendaftaran::STATUS_BERJALAN)->count(),
                'selesai' => $pendaftarans->where('status', Pendaftaran::STATUS_SELESAI)->count(),
            ];
        });

        return view('admin.rekap.per-prodi', compact('prodis'));
    }

    public function perMitra(): View
    {
        $mitras = Mitra::query()
            ->withCount('programs')
            ->get()
            ->map(function ($mitra) {
                $programs = $mitra->programs;
                $pendaftarans = Pendaftaran::whereIn('program_id', $programs->pluck('id'))->get();
                
                return [
                    'mitra' => $mitra,
                    'total_program' => $programs->count(),
                    'total_peserta' => $pendaftarans->count(),
                    'selesai' => $pendaftarans->where('status', Pendaftaran::STATUS_SELESAI)->count(),
                ];
            });

        return view('admin.rekap.per-mitra', compact('mitras'));
    }

    public function eksporExcel(Request $request): StreamedResponse
    {
        $pendaftarans = Pendaftaran::query()
            ->with(['mahasiswa.user', 'program.mitra', 'dosenPembimbing.user'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('prodi'), fn($q) =>
                $q->whereHas('mahasiswa', fn($qq) => $qq->where('prodi', $request->string('prodi')))
            )
            ->when($request->filled('mitra'), fn($q) => 
                $q->whereHas('program', fn($qq) => $qq->where('mitra_id', $request->integer('mitra')))
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->streamDownload(function () use ($pendaftarans) {
            $handle = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($handle, [
                'ID', 'Mahasiswa', 'NIM', 'Prodi', 'Program', 'Mitra', 
                'Dosen Pembimbing', 'Status', 'Tanggal Daftar', 'Tanggal Selesai'
            ]);

            // Data rows
            foreach ($pendaftarans as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->mahasiswa->user->name ?? '-',
                    $p->mahasiswa->nim ?? '-',
                    $p->mahasiswa->prodi ?? '-',
                    $p->program->judul ?? '-',
                    $p->program->mitra->nama_perusahaan ?? '-',
                    $p->dosenPembimbing->user->name ?? '-',
                    $p->labelStatus(),
                    $p->created_at?->format('Y-m-d'),
                    $p->selesai_at?->format('Y-m-d') ?? '-',
                ]);
            }

            fclose($handle);
        }, 'rekap-pendaftaran-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
