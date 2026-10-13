@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6">
    <a href="{{ route('mitra.penilaian.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <h1 class="mt-2 text-xl font-bold text-slate-800">Penilaian: {{ $pendaftaran->mahasiswa->user->name }}</h1>
    <p class="text-sm text-slate-500">{{ $pendaftaran->program->judul }} &middot; Status: {{ ucfirst(str_replace('_', ' ', $pendaftaran->status)) }}</p>

    @if (session('status'))
        <p class="mt-4 rounded bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('status') }}</p>
    @endif

    @if ($sudahFinal)
        <p class="mt-4 rounded bg-amber-50 px-4 py-2 text-sm text-amber-700">
            Nilai sudah final (terkunci)@if($nilaiAkhir !== null) — nilai akhir: <strong>{{ $nilaiAkhir }}</strong>@endif.
            Menunggu nilai final dari dosen agar pendaftaran otomatis selesai.
        </p>
    @endif

    <form method="POST" action="{{ route('mitra.penilaian.update', $pendaftaran) }}" class="mt-4 rounded-lg border border-slate-200 bg-white p-4">
        @csrf @method('PUT')
        <table class="w-full text-sm">
            <thead class="text-left text-xs uppercase text-slate-500">
                <tr><th class="py-2">Komponen</th><th class="py-2">Bobot</th><th class="py-2">Nilai (0–100)</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($rubrik as $key => $meta)
                    <tr>
                        <td class="py-2 font-medium">{{ $meta['label'] }}</td>
                        <td class="py-2">{{ $meta['bobot'] }}%</td>
                        <td class="py-2">
                            <input type="number" name="nilai[{{ $key }}]" min="0" max="100" required
                                value="{{ old('nilai.' . $key, $nilai[$key]->nilai ?? '') }}"
                                @if($sudahFinal) disabled @endif
                                class="w-24 rounded border border-slate-300 px-2 py-1">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @error('nilai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

        @if (!$sudahFinal)
            <div class="mt-4 flex gap-2">
                <button name="final" value="0" class="rounded border border-slate-300 px-4 py-1.5 text-sm hover:bg-slate-50">Simpan draf</button>
                <button name="final" value="1" onclick="return confirm('Finalisasi mengunci nilai permanen. Lanjut?')"
                    class="rounded bg-blue-600 px-4 py-1.5 text-sm text-white hover:bg-blue-700">Simpan &amp; finalisasi</button>
            </div>
        @endif
    </form>
</div>
@endsection
