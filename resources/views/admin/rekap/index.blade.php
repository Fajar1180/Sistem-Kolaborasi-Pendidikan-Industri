@extends('layouts.app')

@section('title', 'Pengawasan Program dan Rekap')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900">Pengawasan Program</h1>
    </div>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">Total Pendaftaran</p>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">Berjalan</p>
            <p class="text-3xl font-bold text-teal-600">{{ $stats['berjalan'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">Selesai</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">Menunggu Review</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['diajukan'] }}</p>
        </div>
    </div>

    <!-- Tab Rekap -->
    <div class="flex gap-2 border-b border-slate-200">
        <a href="{{ route('admin.rekap.index') }}" class="px-4 py-2 font-medium {{ request()->routeIs('admin.rekap.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
            Semua Pengajuan
        </a>
        <a href="{{ route('admin.rekap.program') }}" class="px-4 py-2 font-medium {{ request()->routeIs('admin.rekap.program') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
            Per Program
        </a>
        <a href="{{ route('admin.rekap.prodi') }}" class="px-4 py-2 font-medium {{ request()->routeIs('admin.rekap.prodi') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
            Per Prodi
        </a>
        <a href="{{ route('admin.rekap.mitra') }}" class="px-4 py-2 font-medium {{ request()->routeIs('admin.rekap.mitra') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
            Per Mitra
        </a>
    </div>

    <!-- Filter & Ekspor -->
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                        <option value="">Semua Status</option>
                        <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                        <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui_dosen" {{ request('status') === 'disetujui_dosen' ? 'selected' : '' }}>Disetujui Dosen</option>
                        <option value="diterima_mitra" {{ request('status') === 'diterima_mitra' ? 'selected' : '' }}>Diterima Mitra</option>
                        <option value="berjalan" {{ request('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Prodi</label>
                    <select name="prodi" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Mitra</label>
                    <select name="mitra" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                        <option value="">Semua Mitra</option>
                        @foreach($mitras as $mitra)
                            <option value="{{ $mitra->id }}" {{ request('mitra') == $mitra->id ? 'selected' : '' }}>{{ $mitra->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">Filter</button>
                    <a href="{{ route('admin.rekap.ekspor-excel', request()->query()) }}" class="rounded bg-green-600 px-3 py-2 text-white hover:bg-green-700">Ekspor</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Pengajuan -->
    <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Mahasiswa</th>
                    <th class="px-4 py-3 text-left font-medium">Program</th>
                    <th class="px-4 py-3 text-left font-medium">Mitra</th>
                    <th class="px-4 py-3 text-left font-medium">Dosen</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($pendaftarans as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $p->mahasiswa->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $p->program->judul ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $p->program->mitra->nama_perusahaan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $p->dosenPembimbing->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @include('partials.status_badge')
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pengajuan.show', $p) }}" class="text-blue-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-600">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $pendaftarans->links() }}
    </div>
</div>
@endsection
