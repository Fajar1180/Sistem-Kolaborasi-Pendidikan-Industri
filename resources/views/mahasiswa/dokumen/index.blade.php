@extends('layouts.app')

@section('title', 'Kelola Dokumen')

@section('content')
    <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Kelola Dokumen</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $pendaftaran->program->judul }} — tambah, ganti, atau hapus berkas selama pendaftaran berjalan.</p>

        @if (session('status'))
            <p class="mt-3 rounded bg-emerald-100 px-3 py-2 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('mahasiswa.dokumen.store', $pendaftaran) }}" enctype="multipart/form-data" class="mt-4 flex flex-wrap items-end gap-3 border-b pb-4">
            @csrf
            <div>
                <label class="block text-xs text-slate-500" for="nama">Nama (opsional)</label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" class="rounded border px-2 py-1 text-sm" placeholder="cth: Sertifikat magang">
            </div>
            <div>
                <label class="block text-xs text-slate-500" for="berkas">Berkas (PDF/JPG/PNG/DOCX, maks 5 MB)</label>
                <input id="berkas" type="file" name="berkas" required class="text-sm">
            </div>
            <button type="submit" class="rounded bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">Unggah</button>
        </form>
        @error('berkas')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($dokumens->isEmpty())
            <p class="mt-3 text-sm text-slate-500">Belum ada dokumen. Unggah berkas pertama di atas.</p>
        @else
            <ul class="mt-3 divide-y divide-slate-100">
                @foreach ($dokumens as $dokumen)
                    <li class="flex flex-wrap items-center gap-2 py-3 text-sm">
                        <a href="{{ route('dokumen.unduh', $dokumen) }}" class="font-medium text-blue-600 hover:underline">{{ $dokumen->nama }}</a>
                        <span class="text-xs uppercase text-slate-400">{{ $dokumen->tipe }} &middot; {{ number_format($dokumen->ukuran / 1024, 1) }} KB</span>
                        <form method="POST" action="{{ route('mahasiswa.dokumen.update', [$pendaftaran, $dokumen]) }}" enctype="multipart/form-data" class="ml-auto flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="file" name="berkas" required class="text-xs">
                            <button type="submit" class="rounded bg-amber-500 px-2 py-1 text-xs text-white hover:bg-amber-600">Ganti</button>
                        </form>
                        <form method="POST" action="{{ route('mahasiswa.dokumen.destroy', [$pendaftaran, $dokumen]) }}" onsubmit="return confirm('Hapus dokumen ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700">Hapus</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
