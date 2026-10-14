@extends('layouts.app')

@section('title', 'Rekap Per Mitra')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-slate-900">Rekap Per Mitra</h1>

    <div class="flex gap-2 border-b border-slate-200">
        <a href="{{ route('admin.rekap.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Semua Pengajuan</a>
        <a href="{{ route('admin.rekap.program') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Per Program</a>
        <a href="{{ route('admin.rekap.prodi') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Per Prodi</a>
        <a href="{{ route('admin.rekap.mitra') }}" class="border-b-2 border-blue-600 px-4 py-2 font-medium text-blue-600">Per Mitra</a>
    </div>

    <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Mitra</th>
                    <th class="px-4 py-3 text-right font-medium">Total Program</th>
                    <th class="px-4 py-3 text-right font-medium">Total Peserta</th>
                    <th class="px-4 py-3 text-right font-medium">Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($mitras as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $item['mitra']->nama_perusahaan }}</td>
                        <td class="px-4 py-3 text-right">{{ $item['total_program'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $item['total_peserta'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $item['selesai'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-600">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
