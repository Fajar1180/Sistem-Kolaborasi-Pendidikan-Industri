@extends('layouts.app')

@section('title', 'Seleksi Pelamar')

@section('content')
    <a href="{{ route('mitra.program.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Peluang Saya</a>

    <div class="mt-3 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-xl font-semibold">{{ $program->judul }}</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Kuota terpakai <strong>{{ $program->kuotaTerpakai() }}</strong> / {{ $program->kuota }}
                    &middot; sisa <strong>{{ $sisaKuota }}</strong>
                </p>
            </div>
            <span class="rounded px-2 py-0.5 text-xs uppercase {{ $sisaKuota > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $sisaKuota > 0 ? 'Kuota tersedia' : 'Kuota penuh' }}
            </span>
        </div>

        @if ($sisaKuota <= 0)
            <div class="mt-4 rounded bg-amber-100 px-4 py-2 text-sm text-amber-900">
                Kuota program sudah penuh. Pelamar berikutnya belum dapat diterima sampai ada peserta yang selesai.
            </div>
        @endif
    </div>

    <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow-sm">
        @if ($pelamars->isEmpty())
            <p class="px-4 py-6 text-sm text-slate-500">
                Belum ada pelamar yang menunggu seleksi (status <strong>Disetujui Dosen</strong>).
            </p>
        @else
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Mahasiswa</th>
                        <th class="px-4 py-2">Program Studi</th>
                        <th class="px-4 py-2">Disetujui Dosen</th>
                        <th class="px-4 py-2">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($pelamars as $p)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-700">{{ $p->mahasiswa?->user?->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $p->mahasiswa?->nim }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $p->mahasiswa?->prodi ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $p->disetujui_dosen_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start">
                                    <form method="POST" action="{{ route('mitra.pelamar.terima', $p) }}"
                                          onsubmit="return confirm('Terima {{ $p->mahasiswa?->user?->name }} sebagai peserta?');">
                                        @csrf
                                        <button type="submit" @disabled($sisaKuota <= 0)
                                                class="rounded bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                                            Terima
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('mitra.pelamar.tolak', $p) }}" class="flex flex-col gap-1">
                                        @csrf
                                        <textarea name="catatan" rows="1" required maxlength="2000"
                                                  class="w-56 rounded border border-slate-300 px-2 py-1 text-xs"
                                                  placeholder="Alasan penolakan (wajib)">{{ old('catatan') }}</textarea>
                                        <button type="submit"
                                                class="self-start rounded bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">{{ $pelamars->links() }}</div>
@endsection
