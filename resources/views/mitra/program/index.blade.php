@extends('layouts.app')

@section('title', 'Peluang Saya')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold">Peluang Saya</h2>
            <p class="mt-1 text-sm text-slate-500">Hanya peluang berstatus <strong>published</strong> yang tampil ke mahasiswa.</p>
        </div>
        @if ($mitra)
            <a href="{{ route('mitra.program.create') }}"
               class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Buat Peluang</a>
        @endif
    </div>

    @unless ($mitra)
        <div class="mt-4 rounded bg-amber-100 px-4 py-3 text-sm text-amber-900">
            Akun Anda belum memiliki profil perusahaan.
            <a href="{{ route('mitra.profil.edit') }}" class="font-medium underline">Lengkapi profil dulu</a>
            sebelum membuat peluang.
        </div>
    @endunless

    <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Kuota</th>
                    <th class="px-4 py-3">Pendaftar</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($programs as $program)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $program->judul }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', $program->jenis) }}</td>
                        <td class="px-4 py-3 text-slate-500">
                            {{ $program->tanggal_mulai?->format('d/m/Y') }} &ndash; {{ $program->tanggal_selesai?->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $program->sisaKuota() }} / {{ $program->kuota }} tersisa
                        </td>
                        <td class="px-4 py-3">{{ $program->pendaftarans_count }}</td>
                        <td class="px-4 py-3">
                            @php
                                $warna = match ($program->status_publikasi) {
                                    'published' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-slate-200 text-slate-700',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <span class="rounded px-2 py-0.5 text-xs uppercase {{ $warna }}">{{ $program->status_publikasi }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('mitra.program.edit', $program) }}" class="text-blue-600 hover:underline">Ubah</a>

                                @if ($program->status_publikasi !== 'published')
                                    <form method="POST" action="{{ route('mitra.program.publish', $program) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-700 hover:underline">Publikasikan</button>
                                    </form>
                                @endif

                                @if ($program->status_publikasi !== 'closed')
                                    <form method="POST" action="{{ route('mitra.program.close', $program) }}"
                                          onsubmit="return confirm('Tutup peluang {{ $program->judul }}? Pendaftaran baru akan dihentikan.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-700 hover:underline">Tutup</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Belum ada peluang. Klik <strong>Buat Peluang</strong> untuk menambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
