@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Tambah Pengguna</h2>
        <p class="mt-1 text-sm text-slate-500">Profil peran dibuat otomatis sesuai peran yang dipilih.</p>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 space-y-4">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Kata Sandi</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                </div>
                <div class="sm:col-span-2">
                    <label for="role" class="block text-sm font-medium">Peran</label>
                    <select id="role" name="role" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                        @foreach (['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'mitra' => 'Mitra Industri', 'admin' => 'Admin'] as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('role') === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <fieldset data-peran="mahasiswa" class="space-y-4 rounded border border-slate-200 p-4">
                <legend class="px-1 text-sm font-medium">Data Mahasiswa</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nim" class="block text-sm font-medium">NIM</label>
                        <input id="nim" name="nim" type="text" value="{{ old('nim') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="angkatan" class="block text-sm font-medium">Angkatan</label>
                        <input id="angkatan" name="angkatan" type="number" value="{{ old('angkatan') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="prodi_mhs" class="block text-sm font-medium">Program Studi</label>
                        <input id="prodi_mhs" name="prodi" type="text" list="daftar-prodi" value="{{ old('prodi') }}"
                               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium">No. HP</label>
                        <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                </div>
            </fieldset>

            <fieldset data-peran="dosen" class="space-y-4 rounded border border-slate-200 p-4">
                <legend class="px-1 text-sm font-medium">Data Dosen</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nidn" class="block text-sm font-medium">NIDN</label>
                        <input id="nidn" name="nidn" type="text" value="{{ old('nidn') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="prodi_dosen" class="block text-sm font-medium">Program Studi</label>
                        <input id="prodi_dosen" name="prodi" type="text" list="daftar-prodi" value="{{ old('prodi') }}"
                               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                </div>
            </fieldset>

            <fieldset data-peran="mitra" class="space-y-4 rounded border border-slate-200 p-4">
                <legend class="px-1 text-sm font-medium">Data Mitra</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nama_perusahaan" class="block text-sm font-medium">Nama Perusahaan</label>
                        <input id="nama_perusahaan" name="nama_perusahaan" type="text" value="{{ old('nama_perusahaan') }}"
                               class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="kontak" class="block text-sm font-medium">Kontak</label>
                        <input id="kontak" name="kontak" type="text" value="{{ old('kontak') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-sm font-medium">Alamat</label>
                        <input id="alamat" name="alamat" type="text" value="{{ old('alamat') }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <datalist id="daftar-prodi">
                @foreach ($programStudis as $prodi)
                    <option value="{{ $prodi->nama }}"></option>
                @endforeach
            </datalist>

            <div class="flex gap-3">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="rounded border border-slate-300 px-4 py-2 text-sm">Batal</a>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const role = document.getElementById('role');
            const sections = document.querySelectorAll('fieldset[data-peran]');
            const prodiInputs = document.querySelectorAll('#prodi_mhs, #prodi_dosen');

            function toggle() {
                const aktif = role.value;
                sections.forEach(function (section) {
                    const tampil = section.dataset.peran === aktif;
                    section.classList.toggle('hidden', !tampil);
                    section.querySelectorAll('input, textarea, select').forEach(function (input) {
                        input.disabled = !tampil;
                    });
                });
            }

            role.addEventListener('change', toggle);
            toggle();
        })();
    </script>
@endsection
