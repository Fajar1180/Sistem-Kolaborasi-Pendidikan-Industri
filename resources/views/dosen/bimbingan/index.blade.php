@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-6">
    <h1 class="text-xl font-bold text-slate-800">Bimbingan Mahasiswa</h1>
    <p class="mt-1 text-sm text-slate-500">Mahasiswa yang dibimbing Anda — klik untuk riwayat bimbingan.</p>

    @if ($pendaftarans->isEmpty())
        <p class="mt-6 rounded bg-slate-100 px-4 py-3 text-sm text-slate-500">Belum ada mahasiswa bimbingan.</p>
    @else
        <div class="mt-4 overflow-hidden rounded-lg border border-slate-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Mahasiswa</th>
                        <th class="px-4 py-2">Program</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Catatan</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($pendaftarans as $p)
                        <tr>
                            <td class="px-4 py-2 font-medium">{{ $p->mahasiswa->user->name }}</td>
                            <td class="px-4 py-2">{{ $p->program->judul }}</td>
                            <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</td>
                            <td class="px-4 py-2">{{ $p->bimbingans_count }}x</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('dosen.bimbingan.show', $p) }}" class="text-blue-600 hover:underline">Bimbingan</a>
                                <span class="mx-1 text-slate-300">|</span>
                                <a href="{{ route('dosen.penilaian.edit', $p) }}" class="text-blue-600 hover:underline">Nilai</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pendaftarans->links() }}</div>
    @endif
</div>
@endsection
