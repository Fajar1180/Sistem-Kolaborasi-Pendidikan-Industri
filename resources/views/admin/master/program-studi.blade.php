@extends('layouts.app')

@section('title', 'Data Master — Program Studi')

@section('content')
    <h2 class="text-xl font-semibold">Data Master — Program Studi</h2>

    <form method="POST" action="{{ route('admin.master.program-studi.store') }}" class="mt-4 grid gap-3 rounded-lg bg-white p-4 shadow-sm sm:grid-cols-5">
        @csrf
        <input name="kode" type="text" placeholder="Kode (mis. TI)" required class="rounded border border-slate-300 px-3 py-2 text-sm">
        <input name="nama" type="text" placeholder="Nama program studi" required class="rounded border border-slate-300 px-3 py-2 text-sm sm:col-span-2">
        <select name="jenjang" class="rounded border border-slate-300 px-3 py-2 text-sm">
            @foreach (\App\Models\ProgramStudi::JENJANG as $jenjang)
                <option value="{{ $jenjang }}" @selected($jenjang === 'S1')>{{ $jenjang }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Tambah</button>
    </form>

    <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Jenjang</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.master.program-studi.update', $item) }}" class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input name="kode" type="text" value="{{ $item->kode }}" required class="w-24 rounded border border-slate-300 px-2 py-1">
                                <input name="nama" type="text" value="{{ $item->nama }}" required class="w-48 rounded border border-slate-300 px-2 py-1">
                                <select name="jenjang" class="rounded border border-slate-300 px-2 py-1">
                                    @foreach (\App\Models\ProgramStudi::JENJANG as $jenjang)
                                        <option value="{{ $jenjang }}" @selected($item->jenjang === $jenjang)>{{ $jenjang }}</option>
                                    @endforeach
                                </select>
                                <label class="flex items-center gap-1 text-xs">
                                    <input type="hidden" name="is_aktif" value="0">
                                    <input type="checkbox" name="is_aktif" value="1" @checked($item->is_aktif)> Aktif
                                </label>
                                <button type="submit" class="rounded bg-slate-800 px-3 py-1 text-xs text-white">Simpan</button>
                            </form>
                        </td>
                        <td class="px-4 py-3">&mdash;</td>
                        <td class="px-4 py-3">&mdash;</td>
                        <td class="px-4 py-3">
                            <span class="rounded px-2 py-0.5 text-xs {{ $item->is_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.master.program-studi.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus {{ $item->nama }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada data program studi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
