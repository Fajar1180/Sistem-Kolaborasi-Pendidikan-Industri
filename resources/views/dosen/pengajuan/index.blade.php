@extends('layouts.app')

@section('title', 'Pengajuan Masuk')

@section('content')
    <h2 class="text-xl font-semibold">Pengajuan Masuk</h2>
    <p class="mt-1 text-sm text-slate-500">
        @if ($dosen ?? false)
            Pengajuan penempatan yang ditujukan kepada Anda sebagai dosen pembimbing.
        @else
            Seluruh pengajuan penempatan (tampilan pengawasan admin, hanya-baca).
        @endif
    </p>

    @if (($dosen ?? null) === null && auth()->user()->isDosen())
        <div class="mt-4 rounded bg-amber-100 px-4 py-2 text-amber-900">
            Profil dosen Anda belum lengkap. Hubungi administrator.
        </div>
    @else
        <div class="mt-4 flex flex-wrap gap-2 text-sm">
            <a href="{{ route(Route::currentRouteName(), ['filter' => 'menunggu']) }}"
               class="rounded-full border px-3 py-1 {{ $filter === 'menunggu' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600' }}">
                Menunggu saya ({{ $jumlah['menunggu'] }})
            </a>
            <a href="{{ route(Route::currentRouteName(), ['filter' => 'perlu_revisi']) }}"
               class="rounded-full border px-3 py-1 {{ $filter === 'perlu_revisi' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600' }}">
                Perlu revisi ({{ $jumlah['perlu_revisi'] }})
            </a>
            <a href="{{ route(Route::currentRouteName(), ['filter' => 'riwayat']) }}"
               class="rounded-full border px-3 py-1 {{ $filter === 'riwayat' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600' }}">
                Riwayat
            </a>
            <a href="{{ route(Route::currentRouteName(), ['filter' => 'semua']) }}"
               class="rounded-full border px-3 py-1 {{ $filter === 'semua' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600' }}">
                Semua
            </a>
        </div>

        <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow-sm">
            @if ($pendaftarans->isEmpty())
                <p class="px-4 py-6 text-sm text-slate-500">Tidak ada pengajuan pada kategori ini.</p>
            @else
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-2">Mahasiswa</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Diajukan</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pendaftarans as $p)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-slate-700">{{ $p->mahasiswa?->user?->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">{{ $p->mahasiswa?->nim }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <div>{{ $p->program?->judul }}</div>
                                    <div class="text-xs text-slate-400">{{ $p->program?->mitra?->nama_perusahaan }}</div>
                                </td>
                                <td class="px-4 py-2 text-slate-500">{{ $p->diajukan_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-2">@include('partials.status_badge', ['pendaftaran' => $p])</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('dosen.pengajuan.show', $p) }}" class="text-blue-600 hover:underline">Periksa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="mt-4">{{ $pendaftarans->links() }}</div>
    @endif
@endsection
