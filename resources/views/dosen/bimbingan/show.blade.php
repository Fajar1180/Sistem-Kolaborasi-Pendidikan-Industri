@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6">
    <a href="{{ route('dosen.bimbingan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <h1 class="mt-2 text-xl font-bold text-slate-800">Bimbingan: {{ $pendaftaran->mahasiswa->user->name }}</h1>
    <p class="text-sm text-slate-500">
        {{ $pendaftaran->program->judul }} &middot; {{ $pendaftaran->program->mitra->nama_perusahaan ?? '' }}
        &middot; Status: {{ ucfirst(str_replace('_', ' ', $pendaftaran->status)) }}
    </p>

    <div class="mt-3 flex flex-wrap gap-3 text-sm">
        <span class="rounded bg-slate-100 px-3 py-1">Kegiatan: {{ $jumlahKegiatan }}</span>
        <span class="rounded bg-slate-100 px-3 py-1">Dokumen: {{ $jumlahDokumen }}</span>
        <span class="rounded bg-slate-100 px-3 py-1">Laporan: {{ $laporan ? 'sudah' . ($laporan->terlambat ? ' (terlambat)' : '') : 'belum' }}</span>
        <a href="{{ route('dosen.penilaian.edit', $pendaftaran) }}" class="rounded bg-blue-600 px-3 py-1 text-white hover:bg-blue-700">Beri Nilai</a>
    </div>

    @if (session('status'))
        <p class="mt-4 rounded bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('status') }}</p>
    @endif

    <h2 class="mt-6 font-semibold text-slate-700">Catat bimbingan baru</h2>
    <form method="POST" action="{{ route('dosen.bimbingan.store', $pendaftaran) }}" class="mt-2 rounded-lg border border-slate-200 bg-white p-4">
        @csrf
        <textarea name="catatan" rows="3" required minlength="10" maxlength="2000" placeholder="Hasil diskusi, arahan, tindak lanjut..."
            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">{{ old('catatan') }}</textarea>
        @error('catatan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        <button class="mt-2 rounded bg-blue-600 px-4 py-1.5 text-sm text-white hover:bg-blue-700">Simpan catatan</button>
    </form>

    <h2 class="mt-6 font-semibold text-slate-700">Riwayat ({{ $bimbingans->count() }})</h2>
    @forelse ($bimbingans as $b)
        <div class="mt-2 rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-400">{{ $b->tanggal->format('d/m/Y') }}</p>
            <p class="mt-1 text-sm text-slate-700">{{ $b->catatan }}</p>
            <div class="mt-2 flex gap-2 text-xs">
                <form method="POST" action="{{ route('dosen.bimbingan.update', [$pendaftaran, $b]) }}" class="flex flex-1 gap-2">
                    @csrf @method('PUT')
                    <input type="text" name="catatan" value="{{ $b->catatan }}" minlength="10" maxlength="2000" required class="flex-1 rounded border border-slate-300 px-2 py-1">
                    <button class="text-blue-600 hover:underline">Ubah</button>
                </form>
                <form method="POST" action="{{ route('dosen.bimbingan.destroy', [$pendaftaran, $b]) }}" onsubmit="return confirm('Hapus catatan ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="mt-2 text-sm text-slate-500">Belum ada catatan bimbingan.</p>
    @endforelse
</div>
@endsection
