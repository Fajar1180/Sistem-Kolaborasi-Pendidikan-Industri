@extends('layouts.app')

@section('title', $program->judul)

@section('content')
    <a href="{{ route('mahasiswa.program.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke peluang</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xl font-semibold">{{ $program->judul }}</h2>
            <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ ucfirst(str_replace('_', ' ', $program->jenis)) }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Penyelenggara: <strong class="text-slate-700">{{ $program->mitra?->nama_perusahaan ?? 'Mitra' }}</strong></p>

        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-xs text-slate-500">Periode</dt>
                <dd>{{ $program->tanggal_mulai->format('d/m/Y') }} – {{ $program->tanggal_selesai->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Kuota tersisa</dt>
                <dd>{{ max(0, $program->kuota - $program->kuota_terpakai) }} dari {{ $program->kuota }}</dd>
            </div>
        </dl>

        <div class="mt-4 text-sm text-slate-700">{{ $program->deskripsi }}</div>

        @if ($program->syarat)
            <div class="mt-4">
                <h3 class="text-sm font-medium">Persyaratan</h3>
                <p class="mt-1 text-sm text-slate-600">{{ $program->syarat }}</p>
            </div>
        @endif
    </div>

    @if ($pendaftaranSaya)
        <div class="mt-4 flex flex-wrap items-center gap-2 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800">
            <span>Anda sudah mendaftar program ini.</span>
            <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaranSaya) }}" class="font-medium underline">Lihat status pendaftaran</a>
        </div>
    @else
        <form method="GET" action="{{ route('mahasiswa.pendaftaran.create', $program) }}" class="mt-4">
            <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700">Daftar Program</button>
        </form>
    @endif
@endsection
