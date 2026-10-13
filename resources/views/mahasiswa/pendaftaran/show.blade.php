@extends('layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
    <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xl font-semibold">{{ $pendaftaran->program->judul }}</h2>
            @include('mahasiswa._status_badge', ['pendaftaran' => $pendaftaran])
        </div>
        <p class="mt-1 text-sm text-slate-500">Mitra: <strong class="text-slate-700">{{ $pendaftaran->program->mitra?->nama_perusahaan ?? 'Mitra' }}</strong></p>

        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-xs text-slate-500">Status</dt>
                <dd>{{ $pendaftaran->labelStatus() }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Diajukan</dt>
                <dd>{{ $pendaftaran->diajukan_at?->format('d/m/Y H:i') ?? '-' }}</dd>
            </div>
        </dl>

        @if ($pendaftaran->bisaDibatalkan())
            <form method="POST" action="{{ route('mahasiswa.pendaftaran.destroy', $pendaftaran) }}" class="mt-5"
                  onsubmit="return confirm('Batalkan pendaftaran ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Batalkan Pendaftaran</button>
            </form>
        @else
            <p class="mt-5 text-sm text-slate-500">Pendaftaran yang sudah diterima/berjalan/selesai tidak dapat dibatalkan sendiri.</p>
        @endif
    </div>
@endsection
