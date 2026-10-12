@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi')
@section('subtitle', 'Masukkan email untuk menerima tautan atur ulang')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 px-3 py-2 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
            Kirim Tautan Atur Ulang
        </button>

        <a href="{{ route('login') }}" class="block text-center text-sm text-blue-600 hover:underline">Kembali ke halaman masuk</a>
    </form>
@endsection
