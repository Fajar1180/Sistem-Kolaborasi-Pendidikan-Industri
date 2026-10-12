@extends('layouts.app')

@section('title', 'Impor Data Pengguna')

@section('content')
    <div class="mx-auto max-w-3xl">
        <h2 class="text-xl font-semibold">Impor Data Pengguna dari CSV</h2>
        <p class="mt-1 text-sm text-slate-500">
            Kolom wajib: <code>nama</code>, <code>email</code>, <code>role</code>. Kolom lain mengikuti peran
            (mahasiswa: nim, prodi, angkatan, no_hp; dosen: nidn, prodi; mitra: nama_perusahaan, alamat, kontak).
        </p>

        <div class="mt-4 rounded-lg bg-white p-6 shadow-sm">
            <a href="{{ route('admin.import.template') }}" class="text-sm text-blue-600 hover:underline">
                Unduh template CSV
            </a>

            <form method="POST" action="{{ route('admin.import.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="file" class="block text-sm font-medium">Berkas CSV (maks 2 MB)</label>
                    <input id="file" name="file" type="file" accept=".csv,.txt" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Impor</button>
            </form>

            <p class="mt-3 text-xs text-slate-500">
                Kata sandi awal untuk setiap akun hasil impor: <strong>{{ $kataSandiAwal }}</strong>.
                Pengguna disarankan segera menggantinya.
            </p>
        </div>

        @if (! empty($hasil))
            <div class="mt-6 rounded-lg bg-white p-6 shadow-sm">
                <h3 class="font-semibold">Ringkasan Impor</h3>

                <p class="mt-2 text-sm text-green-700">Berhasil: {{ count($hasil['berhasil'] ?? []) }} akun</p>
                @if (! empty($hasil['berhasil']))
                    <ul class="mt-1 list-inside list-disc text-xs text-slate-600">
                        @foreach ($hasil['berhasil'] as $email)
                            <li>{{ $email }}</li>
                        @endforeach
                    </ul>
                @endif

                <p class="mt-4 text-sm text-red-700">Gagal: {{ count($hasil['gagal'] ?? []) }} baris</p>
                @if (! empty($hasil['gagal']))
                    <table class="mt-2 min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="text-left uppercase text-slate-500">
                            <tr>
                                <th class="py-1 pr-4">Baris</th>
                                <th class="py-1 pr-4">Email</th>
                                <th class="py-1">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($hasil['gagal'] as $gagal)
                                <tr>
                                    <td class="py-1 pr-4">{{ $gagal['baris'] }}</td>
                                    <td class="py-1 pr-4">{{ $gagal['email'] }}</td>
                                    <td class="py-1">{{ $gagal['alasan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
    </div>
@endsection
