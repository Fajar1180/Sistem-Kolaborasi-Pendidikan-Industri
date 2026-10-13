@extends('layouts.app')

@section('title', 'Ubah Peluang')

@section('content')
    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold">Ubah Peluang</h2>
            @php
                $warna = match ($program->status_publikasi) {
                    'published' => 'bg-green-100 text-green-800',
                    'closed' => 'bg-slate-200 text-slate-700',
                    default => 'bg-amber-100 text-amber-800',
                };
            @endphp
            <span class="rounded px-2 py-0.5 text-xs uppercase {{ $warna }}">{{ $program->status_publikasi }}</span>
        </div>

        @if ($program->status_publikasi === 'published')
            <p class="mt-2 text-sm text-slate-500">
                Peluang sudah dipublikasikan. Ubahan tetap tersimpan; untuk menghentikan pendaftaran gunakan tombol <strong>Tutup</strong>.
            </p>
        @endif

        <form method="POST" action="{{ route('mitra.program.update', $program) }}" class="mt-6 space-y-4">
            @method('PUT')
            @include('mitra.program._form')
        </form>
    </div>
@endsection
