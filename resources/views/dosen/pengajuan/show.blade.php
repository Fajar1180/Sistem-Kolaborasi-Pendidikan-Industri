@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
    <a href="{{ route('dosen.pengajuan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-xl font-semibold">{{ $pendaftaran->program?->judul }}</h2>
            @include('partials.status_badge', ['pendaftaran' => $pendaftaran])
        </div>

        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-xs text-slate-500">Mahasiswa</dt>
                <dd>{{ $pendaftaran->mahasiswa?->user?->name ?? '-' }} ({{ $pendaftaran->mahasiswa?->nim }})</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Program studi</dt>
                <dd>{{ $pendaftaran->mahasiswa?->prodi ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Mitra</dt>
                <dd>{{ $pendaftaran->program?->mitra?->nama_perusahaan ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Diajukan</dt>
                <dd>{{ $pendaftaran->diajukan_at?->format('d/m/Y H:i') ?? '-' }}</dd>
            </div>
        </dl>

        @if ($pendaftaran->catatan_mahasiswa)
            <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <span class="font-medium text-slate-700">Catatan mahasiswa:</span> {{ $pendaftaran->catatan_mahasiswa }}
            </div>
        @endif

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
    </div>

    @if ($bolehMemutuskan)
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            {{-- Setujui --}}
            <form method="POST" action="{{ route('dosen.pengajuan.setujui', $pendaftaran) }}"
                  class="rounded-lg bg-white p-4 shadow-sm" onsubmit="return confirm('Setujui pengajuan ini?');">
                @csrf
                <h3 class="font-medium text-slate-700">Setujui</h3>
                <p class="mt-1 text-xs text-slate-500">Teruskan pengajuan ke antrean seleksi mitra.</p>
                <button class="mt-3 w-full rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Setujui</button>
            </form>

            {{-- Minta revisi --}}
            <form method="POST" action="{{ route('dosen.pengajuan.revisi', $pendaftaran) }}" class="rounded-lg bg-white p-4 shadow-sm">
                @csrf
                <h3 class="font-medium text-slate-700">Minta Revisi</h3>
                <textarea name="catatan" rows="3" required maxlength="2000"
                          class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm"
                          placeholder="Catatan revisi (wajib)">{{ old('catatan') }}</textarea>
                <button class="mt-2 w-full rounded bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">Minta Revisi</button>
            </form>

            {{-- Tolak --}}
            <form method="POST" action="{{ route('dosen.pengajuan.tolak', $pendaftaran) }}"
                  class="rounded-lg bg-white p-4 shadow-sm" onsubmit="return confirm('Tolak pengajuan ini?');">
                @csrf
                <h3 class="font-medium text-slate-700">Tolak</h3>
                <textarea name="catatan" rows="3" required maxlength="2000"
                          class="mt-2 w-full rounded border border-slate-300 px-2 py-1 text-sm"
                          placeholder="Alasan penolakan (wajib)">{{ old('catatan') }}</textarea>
                <button class="mt-2 w-full rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
            </form>
        </div>
    @else
        <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Pengajuan ini tidak menunggu keputusan Anda (status: {{ $pendaftaran->labelStatus() }}).
            @if ($pendaftaran->catatan)
                <div class="mt-1">Catatan keputusan: {{ $pendaftaran->catatan }}</div>
            @endif
        </div>
    @endif
@endsection
