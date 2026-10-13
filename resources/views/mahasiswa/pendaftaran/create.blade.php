@extends('layouts.app')

@section('title', 'Konfirmasi Pendaftaran')

@section('content')
    <a href="{{ route('mahasiswa.program.show', $program) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Konfirmasi Pendaftaran</h2>
        <p class="mt-1 text-sm text-slate-500">Pastikan data berikut benar sebelum Anda mendaftar.</p>

        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-xs text-slate-500">Program</dt>
                <dd>{{ $program->judul }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Mitra</dt>
                <dd>{{ $program->mitra?->nama_perusahaan ?? 'Mitra' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Periode</dt>
                <dd>{{ $program->tanggal_mulai->format('d/m/Y') }} – {{ $program->tanggal_selesai->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Kuota tersisa</dt>
                <dd>{{ $program->sisaKuota() }}</dd>
            </div>
        </dl>

        <form method="POST" action="{{ route('mahasiswa.pendaftaran.store', $program) }}" class="mt-5">
            @csrf
            <p class="text-sm text-slate-600">Dengan mendaftar, pengajuan Anda akan diteruskan ke dosen pembimbing untuk disetujui.</p>
            <div class="mt-4 flex items-center gap-2">
                <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700">Ya, Daftar</button>
                <a href="{{ route('mahasiswa.program.show', $program) }}" class="px-4 py-2 text-sm text-slate-600 hover:underline">Batal</a>
            </div>
        </form>
    </div>
@endsection
