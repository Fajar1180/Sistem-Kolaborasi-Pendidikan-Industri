@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold">Akun Saya</h2>
            <p class="mt-1 text-sm text-slate-500">
                Peran Anda: <strong>{{ $user->role }}</strong>. Peran tidak dapat diubah dari halaman ini.
            </p>

            @if (session('status'))
                <div class="mt-4 rounded bg-green-100 px-4 py-2 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" value="{{ $user->email }}" disabled
                           class="mt-1 w-full rounded border border-slate-200 bg-slate-100 px-3 py-2 text-slate-500">
                </div>

                <hr class="border-slate-200">

                <p class="text-sm text-slate-500">Biarkan kosong bila tidak ingin mengganti kata sandi.</p>

                <div>
                    <label for="password" class="block text-sm font-medium">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" autocomplete="new-password"
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Kata Sandi Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                           class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                </div>

                <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
