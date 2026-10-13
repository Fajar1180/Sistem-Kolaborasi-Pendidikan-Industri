@extends('layouts.app')

@section('title', 'Catat Kegiatan')

@section('content')
    <a href="{{ route('mahasiswa.kegiatan.index', $pendaftaran) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Catat Kegiatan</h2>
        <p class="mt-1 text-sm text-slate-700">{{ $pendaftaran->program->judul }}</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-900">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('mahasiswa.kegiatan.store', $pendaftaran) }}"
              enctype="multipart/form-data" class="mt-5 space-y-5">
            @csrf

            <div>
                <label for="tanggal" class="block text-sm font-medium text-slate-700">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="uraian" class="block text-sm font-medium text-slate-700">Uraian Kegiatan</label>
                <textarea id="uraian" name="uraian" rows="4" required
                    class="mt-1 block w-full rounded border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('uraian') }}</textarea>
            </div>

            <div>
                <label for="bukti" class="block text-sm font-medium text-slate-700">Bukti (jpg/png/pdf/docx, maks 5MB)</label>
                <input type="file" id="bukti" name="bukti"
                       class="mt-1 block w-full text-sm text-slate-600">
                <p class="mt-1 text-xs text-slate-500">Opsional.</p>
            </div>

            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Simpan
            </button>
        </form>
    </div>
@endsection
