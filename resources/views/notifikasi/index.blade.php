@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-xl font-semibold">Notifikasi</h2>
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
            @csrf
            <button type="submit" class="rounded bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                Tandai semua dibaca
            </button>
        </form>
    </div>

    <div class="mt-4 space-y-2">
        @forelse ($notifikasis as $n)
            <div class="flex flex-wrap items-start justify-between gap-2 rounded-lg bg-white p-4 shadow-sm {{ $n->dibaca_at ? '' : 'border-l-4 border-blue-600' }}">
                <div class="min-w-0 flex-1">
                    <p class="font-medium">{{ $n->judul }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $n->isi }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $n->created_at?->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex shrink-0 gap-2 text-sm">
                    @if ($n->link)
                        <a href="{{ $n->link }}" class="text-blue-600 hover:underline">Buka</a>
                    @endif
                    @unless ($n->dibaca_at)
                        <form method="POST" action="{{ route('notifikasi.baca', $n) }}">
                            @csrf
                            <button type="submit" class="text-emerald-600 hover:underline">Tandai dibaca</button>
                        </form>
                    @endunless
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white p-6 text-center text-sm text-slate-500 shadow-sm">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifikasis->links() }}</div>
@endsection
