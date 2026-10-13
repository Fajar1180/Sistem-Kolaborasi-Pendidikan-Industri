{{-- S2-07a — Thread diskusi komentar per pendaftaran + form kirim.
     Dipakai: @include('partials.komentar', ['pendaftaran' => $pendaftaran])
     Syarat: $pendaftaran->loadMissing('komentars.user') di controller. --}}
@php($komentars = $pendaftaran->komentars ?? collect())
<div class="mt-6">
    <h3 class="text-sm font-semibold text-slate-700">Diskusi ({{ $komentars->count() }})</h3>
    @forelse ($komentars as $k)
        <div class="mt-2 rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-400">{{ $k->created_at?->format('d/m/Y H:i') }} &middot; {{ $k->user?->name ?? 'Pengguna' }}</p>
            <p class="mt-1 text-sm text-slate-700">{{ $k->isi }}</p>
        </div>
    @empty
        <p class="mt-2 text-sm text-slate-500">Belum ada diskusi.</p>
    @endforelse
    @can('create', [\App\Models\Komentar::class, $pendaftaran])
        <form method="POST" action="{{ route('komentar.store', $pendaftaran) }}" class="mt-3">
            @csrf
            <textarea name="isi" rows="2" maxlength="2000" required minlength="3"
                class="w-full rounded border-slate-300 text-sm" placeholder="Tulis komentar..."></textarea>
            <button type="submit" class="mt-2 rounded bg-indigo-600 px-4 py-1.5 text-sm text-white">Kirim</button>
        </form>
    @endcan
</div>
