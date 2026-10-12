@extends('layouts.app')

@section('title', 'Pengelolaan Pengguna')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-semibold">Pengelolaan Pengguna</h2>
        <a href="{{ route('admin.users.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Tambah Pengguna</a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mt-4 grid gap-3 rounded-lg bg-white p-4 shadow-sm sm:grid-cols-4">
        <input type="text" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Cari nama atau email"
               class="rounded border border-slate-300 px-3 py-2 text-sm sm:col-span-2">

        <select name="peran" class="rounded border border-slate-300 px-3 py-2 text-sm">
            <option value="">Semua peran</option>
            @foreach (['admin', 'dosen', 'mitra', 'mahasiswa'] as $peran)
                <option value="{{ $peran }}" @selected(($filter['peran'] ?? '') === $peran)>{{ ucfirst($peran) }}</option>
            @endforeach
        </select>

        <select name="status" class="rounded border border-slate-300 px-3 py-2 text-sm">
            <option value="">Semua status</option>
            <option value="aktif" @selected(($filter['status'] ?? '') === 'aktif')>Aktif</option>
            <option value="nonaktif" @selected(($filter['status'] ?? '') === 'nonaktif')>Nonaktif</option>
        </select>

        <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900 sm:col-span-4">
            Terapkan Filter
        </button>
    </form>

    <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">Data Profil</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3"><span class="rounded bg-slate-200 px-2 py-0.5 text-xs uppercase">{{ $user->role }}</span></td>
                        <td class="px-4 py-3 text-slate-500">
                            @if ($user->mahasiswa)
                                NIM {{ $user->mahasiswa->nim }} &middot; {{ $user->mahasiswa->prodi }}
                            @elseif ($user->dosen)
                                NIDN {{ $user->dosen->nidn }} &middot; {{ $user->dosen->prodi }}
                            @elseif ($user->mitra)
                                {{ $user->mitra->nama_perusahaan }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded px-2 py-0.5 text-xs {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Ubah</a>

                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-amber-600 hover:underline">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Hapus akun {{ $user->email }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Tidak ada pengguna yang cocok dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
