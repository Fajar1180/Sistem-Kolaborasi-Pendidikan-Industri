@extends('layouts.app')

@section('title', 'Pengajuan Penempatan')

@section('content')
    <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xl font-semibold">Pengajuan Penempatan</h2>
            @include('partials.status_badge', ['pendaftaran' => $pendaftaran])
        </div>
        <p class="mt-1 text-sm text-slate-700">{{ $pendaftaran->program->judul }}</p>
        <p class="text-sm text-slate-500">Mitra: {{ $pendaftaran->program->mitra?->nama_perusahaan ?? 'Mitra' }}</p>

        @if (! $bisaDiedit)
            {{-- Status terkunci: tampilkan ringkasan saja --}}
            <div class="mt-4 rounded-lg bg-slate-100 px-4 py-3 text-sm text-slate-600">
                Pengajuan ini tidak dapat diubah pada status {{ strtolower($pendaftaran->labelStatus()) }}.
                @if ($pendaftaran->catatan)
                    <div class="mt-1">Catatan reviewer: {{ $pendaftaran->catatan }}</div>
                @endif
            </div>

            @if ($pendaftaran->dokumens->isNotEmpty())
                <div class="mt-4">
                    <span class="block text-sm font-medium text-slate-700">Berkas Lampiran</span>
                    <ul class="mt-2 divide-y divide-slate-100 rounded border border-slate-200">
                        @foreach ($pendaftaran->dokumens as $dokumen)
                            <li class="flex items-center gap-2 px-3 py-2 text-sm">
                                <a href="{{ route('dokumen.unduh', $dokumen) }}" class="text-blue-600 hover:underline">{{ $dokumen->nama }}</a>
                                <span class="text-xs uppercase text-slate-400">{{ $dokumen->tipe }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            @if ($pendaftaran->status === 'perlu_revisi')
                <div class="mt-4 rounded-lg bg-amber-100 px-4 py-3 text-sm text-amber-900">
                    Dosen meminta revisi. Perbarui berkas/catatan, lalu ajukan ulang.
                    @if ($pendaftaran->catatan)
                        <div class="mt-1 font-medium">Catatan dosen: {{ $pendaftaran->catatan }}</div>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('mahasiswa.pengajuan.update', $pendaftaran) }}"
                  enctype="multipart/form-data" class="mt-5 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="catatan_mahasiswa" class="block text-sm font-medium text-slate-700">Catatan / Motivasi</label>
                    <textarea id="catatan_mahasiswa" name="catatan_mahasiswa" rows="4"
                        class="mt-1 block w-full rounded border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan_mahasiswa', $pendaftaran->catatan_mahasiswa) }}</textarea>
                </div>

                <div>
                    <span class="block text-sm font-medium text-slate-700">Berkas Lampiran</span>
                    <p class="text-xs text-slate-500">PDF, JPG, PNG, atau DOCX — maks 5 MB per berkas, maks 5 berkas sekali unggah.</p>

                    @if ($pendaftaran->dokumens->isNotEmpty())
                        <ul class="mt-2 divide-y divide-slate-100 rounded border border-slate-200">
                            @foreach ($pendaftaran->dokumens as $dokumen)
                                <li class="flex items-center gap-2 px-3 py-2 text-sm">
                                    <input type="checkbox" name="hapus_dokumen[]" value="{{ $dokumen->id }}"
                                           class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                    <a href="{{ route('dokumen.unduh', $dokumen) }}" class="text-blue-600 hover:underline">{{ $dokumen->nama }}</a>
                                    <span class="text-xs uppercase text-slate-400">{{ $dokumen->tipe }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="mt-1 text-xs text-slate-400">Centang berkas untuk dihapus saat menyimpan.</p>
                    @endif

                    <input type="file" name="files[]" multiple
                        class="mt-2 block w-full text-sm text-slate-600 file:mr-3 file:rounded file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="rounded bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Simpan Draf</button>
                    <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" class="text-sm text-slate-500 hover:underline">Batal</a>
                </div>
            </form>

            <form method="POST" action="{{ route('mahasiswa.pengajuan.ajukan', $pendaftaran) }}"
                  class="mt-6 border-t border-slate-100 pt-4"
                  onsubmit="return confirm('Ajukan pengajuan ini ke dosen pembimbing? Setelah diajukan tidak dapat diubah.');">
                @csrf
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Ajukan Penempatan</button>
                <p class="mt-1 text-xs text-slate-400">Pastikan formulir &amp; berkas sudah lengkap.</p>
            </form>
        @endif
    </div>
@endsection
