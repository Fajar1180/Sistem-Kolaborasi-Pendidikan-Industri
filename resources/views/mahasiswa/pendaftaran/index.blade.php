@extends('layouts.app')

@section('title', 'Pendaftaran Saya')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-xl font-semibold">Pendaftaran Saya</h2>
        <a href="{{ route('mahasiswa.program.index') }}" class="text-sm text-blue-600 hover:underline">Cari peluang lain</a>
    </div>

    @unless ($mahasiswa)
        <div class="mt-4 rounded-lg bg-amber-100 px-4 py-3 text-sm text-amber-900">
            Profil mahasiswa Anda belum lengkap. Hubungi administrator untuk dapat mendaftar program.
        </div>
    @endunless

    @forelse ($pendaftarans as $p)
        <a href="{{ route('mahasiswa.pendaftaran.show', $p) }}" class="mt-3 block rounded-lg bg-white p-5 shadow-sm hover:shadow">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="font-medium">{{ $p->program->judul }}</h3>
                @include('mahasiswa._status_badge', ['pendaftaran' => $p])
            </div>
            <p class="mt-1 text-sm text-slate-500">{{ $p->program->mitra?->nama_perusahaan ?? 'Mitra' }}</p>
            <p class="mt-2 text-xs text-slate-400">Diajukan: {{ $p->diajukan_at?->format('d/m/Y H:i') ?? '-' }}</p>
            @if ($p->bisaDieditMahasiswa())
                <p class="mt-1 text-xs font-medium text-blue-600">
                    {{ $p->status === 'draf' ? 'Perlu dilengkapi & diajukan' : 'Perlu revisi & diajukan ulang' }}
                </p>
            @endif
        </a>
    @empty
        <div class="mt-4 rounded-lg bg-white p-6 text-center text-sm text-slate-500 shadow-sm">
            Anda belum memiliki pendaftaran.
            <a href="{{ route('mahasiswa.program.index') }}" class="font-medium text-blue-600 hover:underline">Cari peluang</a>.
        </div>
    @endforelse
@endsection
