@extends('layouts.app')

@section('title', 'Laporan Final')

@section('content')
    <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Laporan Final</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $pendaftaran->program->judul }}</p>

        @if (session('status'))
            <p class="mt-3 rounded bg-emerald-100 px-3 py-2 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif

        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-xs text-slate-500">Tenggat</dt>
                <dd>{{ $tenggat?->format('d/m/Y') ?? 'Tidak ditentukan' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Status waktu</dt>
                <dd>
                    @if (is_null($sisaHari))
                        -
                    @elseif ($sisaHari < 0)
                        <span class="font-semibold text-red-600">Terlambat {{ abs($sisaHari) }} hari</span>
                    @elseif ($sisaHari === 0)
                        <span class="font-semibold text-amber-600">Tenggat hari ini</span>
                    @else
                        <span class="text-emerald-700">{{ $sisaHari }} hari tersisa</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Status laporan</dt>
                <dd>
                    @if (! $laporan)
                        Belum dikumpulkan
                    @else
                        Terkumpul{{ $laporan->terlambat ? ' (TERLAMBAT)' : ' (tepat waktu)' }} &middot; {{ $laporan->status_tinjau }}
                    @endif
                </dd>
            </div>
        </dl>

        <form method="POST" action="{{ route('mahasiswa.laporan.store', $pendaftaran) }}" enctype="multipart/form-data" class="mt-4 flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="block text-xs text-slate-500" for="berkas">Berkas laporan (PDF/DOCX, maks 10 MB){{ $laporan ? ' — unggah lagi untuk revisi' : '' }}</label>
                <input id="berkas" type="file" name="berkas" required class="text-sm">
            </div>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $laporan ? 'Kumpulkan Revisi' : 'Kumpulkan Laporan' }}
            </button>
        </form>
        @error('berkas')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($laporan)
            <form method="POST" action="{{ route('mahasiswa.laporan.destroy', [$pendaftaran, $laporan]) }}" class="mt-4" onsubmit="return confirm('Hapus laporan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-700">Hapus Laporan</button>
            </form>
        @endif
    </div>
@endsection
