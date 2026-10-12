@extends('layouts.guest')

@section('title', 'Masuk')
@section('subtitle', 'Masuk ke akun Anda')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 px-3 py-2 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="rounded border-slate-300"> Ingat saya
        </label>

        <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
            Masuk
        </button>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="block text-center text-sm text-blue-600 hover:underline">
                Lupa kata sandi?
            </a>
        @endif
    </form>
@endsection
