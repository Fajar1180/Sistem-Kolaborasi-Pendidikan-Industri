@extends('layouts.app')

@section('title', 'Catatan Kegiatan')

@section('content')
    <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xl font-semibold">Catatan Kegiatan</h2>
            @include('partials.status_badge', ['pendaftaran' => $pendaftaran])
        </div>
        <p class="mt-1 text-sm text-slate-700">{{ $pendaftaran->program->judul }}</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-900">{{ session('status') }}</div>
        @endif

        <div class="mt-4">
            <a href="{{ route('mahasiswa.kegiatan.create', $pendaftaran) }}"
               class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">+ Catat Kegiatan</a>
        </div>

        <div class="mt-5 divide-y divide-slate-100 rounded border border-slate-200">
            @forelse ($kegiatans as $kegiatan)
                <div class="px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-sm font-medium text-slate-800">{{ $kegiatan->tanggal->format('d M Y') }}</span>
                        <span class="text-xs uppercase text-slate-400">{{ $kegiatan->status_tinjau }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700">{{ $kegiatan->uraian }}</p>
                    @if ($kegiatan->catatan_tinjau)
                        <p class="mt-1 text-sm text-amber-800">Catatan dosen: {{ $kegiatan->catatan_tinjau }}</p>
                    @endif
                    <div class="mt-2 flex gap-3 text-sm">
                        <a href="{{ route('mahasiswa.kegiatan.edit', [$pendaftaran, $kegiatan]) }}"
                           class="text-blue-600 hover:underline">Ubah</a>
                        <form method="POST" action="{{ route('mahasiswa.kegiatan.destroy', [$pendaftaran, $kegiatan]) }}"
                              onsubmit="return confirm('Hapus catatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="px-4 py-6 text-center text-sm text-slate-500">Belum ada kegiatan tercatat.</p>
            @endforelse
        </div>
    </div>
@endsection
