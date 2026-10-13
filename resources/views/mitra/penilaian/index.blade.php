@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-6">
    <h1 class="text-xl font-bold text-slate-800">Penilaian Peserta</h1>
    <p class="mt-1 text-sm text-slate-500">Peserta berjalan di program Anda — klik untuk memberi nilai.</p>

    @if ($pesertas->isEmpty())
        <p class="mt-6 rounded bg-slate-100 px-4 py-3 text-sm text-slate-500">Belum ada peserta berjalan.</p>
    @else
        <div class="mt-4 overflow-hidden rounded-lg border border-slate-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Peserta</th>
                        <th class="px-4 py-2">Program</th>
                        <th class="px-4 py-2">Status Nilai</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($pesertas as $p)
                        <tr>
                            <td class="px-4 py-2 font-medium">{{ $p->mahasiswa->user->name }}</td>
                            <td class="px-4 py-2">{{ $p->program->judul }}</td>
                            <td class="px-4 py-2">
                                @if ($p->penilaian_final_count === 4)
                                    <span class="rounded bg-green-100 px-2 py-0.5 text-xs text-green-700">Final</span>
                                @elseif ($p->penilaian_final_count > 0)
                                    <span class="rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Draf</span>
                                @else
                                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-500">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('mitra.penilaian.edit', $p) }}" class="text-blue-600 hover:underline">Nilai</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pesertas->links() }}</div>
    @endif
</div>
@endsection
