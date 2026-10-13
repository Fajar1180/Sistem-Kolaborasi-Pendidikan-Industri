@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h2 class="text-xl font-semibold">Profil Perusahaan</h2>
        <p class="mt-1 text-sm text-slate-500">Profil ini yang dilihat mahasiswa pada setiap peluang yang Anda publikasikan.</p>

        <form method="POST" action="{{ route('mitra.profil.update') }}" enctype="multipart/form-data"
              class="mt-6 space-y-4 rounded-lg bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_perusahaan" class="block text-sm font-medium">Nama Perusahaan</label>
                <input id="nama_perusahaan" name="nama_perusahaan" type="text" required
                       value="{{ old('nama_perusahaan', $mitra->nama_perusahaan) }}"
                       class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="kontak" class="block text-sm font-medium">Kontak</label>
                    <input id="kontak" name="kontak" type="text" value="{{ old('kontak', $mitra->kontak) }}"
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium">Alamat</label>
                    <input id="alamat" name="alamat" type="text" value="{{ old('alamat', $mitra->alamat) }}"
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="mt-1 w-full rounded border border-slate-300 px-3 py-2">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium">Logo Perusahaan</label>
                @if ($mitra->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mitra->logo_path) }}"
                         alt="Logo {{ $mitra->nama_perusahaan }}" class="mt-2 h-16 w-16 rounded object-contain">
                @endif
                <input id="logo" name="logo" type="file" accept="image/*"
                       class="mt-2 w-full rounded border border-slate-300 px-3 py-2 text-sm">
                <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, WEBP, atau SVG. Maksimal 5 MB.</p>
            </div>

            <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                Simpan Profil
            </button>
        </form>
    </div>
@endsection
