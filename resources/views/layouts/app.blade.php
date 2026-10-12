<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <nav class="bg-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('profile.edit') }}" class="hover:underline">
                        {{ auth()->user()->name }}
                        <span class="rounded bg-slate-200 px-2 py-0.5 text-xs uppercase">{{ auth()->user()->role }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:underline">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Keluar</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-800">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
