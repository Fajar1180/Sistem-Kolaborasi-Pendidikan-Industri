<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-2 px-4 py-3">
            <div class="flex items-center gap-2">
                @auth
                    <button type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 md:hidden"
                            aria-controls="nav-mobile" aria-expanded="false" aria-label="Buka menu"
                            onclick="document.getElementById('nav-mobile').classList.toggle('hidden')">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                    </button>
                @endauth
                <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>
            </div>
            @auth
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    @php($notifBelum = auth()->user()->notifikasis()->belumDibaca()->count())
                    @php($notifBaru = auth()->user()->notifikasis()->latest()->limit(5)->get())
                    <div class="relative">
                        <a href="{{ route('notifikasi.index') }}" class="hover:underline">🔔
                            @if ($notifBelum)
                                <span class="rounded-full bg-red-600 px-1.5 text-xs text-white">{{ $notifBelum }}</span>
                            @endif
                        </a>
                        @if ($notifBaru->isNotEmpty())
                            <div class="absolute right-0 z-10 mt-1 w-64 rounded border border-slate-200 bg-white p-2 shadow">
                                @foreach ($notifBaru as $nb)
                                    <a href="{{ $nb->link ?: route('notifikasi.index') }}" class="block truncate px-2 py-1 text-xs hover:bg-slate-50">{{ $nb->judul }}</a>
                                @endforeach
                                <a href="{{ route('notifikasi.index') }}" class="block px-2 py-1 text-xs font-medium text-blue-600 hover:underline">Lihat arsip →</a>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}" class="hover:underline">
                        {{ auth()->user()->name }}
                        <span class="rounded bg-slate-200 px-2 py-0.5 text-xs uppercase">{{ auth()->user()->role }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:underline">Dashboard</a>
                    <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-700 font-medium text-sm">Keluar</a>
                </div>
            @endauth
        </div>
        @auth
            <div id="nav-mobile" class="hidden border-t border-slate-200 bg-slate-50 md:block">
                <div class="mx-auto flex max-w-6xl flex-col gap-1 px-4 py-2 text-sm md:flex-row md:flex-wrap md:gap-4">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Pengguna</a>
                        <a href="{{ route('admin.master.program-studi.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Program Studi</a>
                        <a href="{{ route('admin.master.periode-akademik.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Periode Akademik</a>
                        <a href="{{ route('admin.import.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Impor CSV</a>
                        <a href="{{ route('admin.pengajuan.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Pengajuan Masuk</a>
                    @elseif (auth()->user()->isDosen())
                        <a href="{{ route('dosen.pengajuan.index') }}" class="nav-link rounded px-2 py-1">Pengajuan</a>
                        <a href="{{ route('dosen.bimbingan.index') }}" class="nav-link rounded px-2 py-1">Bimbingan</a>
                    @elseif (auth()->user()->isMitra())
                        <a href="{{ route('mitra.profil.edit') }}" class="nav-link rounded px-2 py-1">Profil</a>
                        <a href="{{ route('mitra.program.index') }}" class="nav-link rounded px-2 py-1">Program</a>
                        <a href="{{ route('mitra.penilaian.index') }}" class="nav-link rounded px-2 py-1">Penilaian</a>
                    @endif
                    @if (auth()->user()->isMahasiswa())
                        <a href="{{ route('mahasiswa.program.index') }}" class="nav-link rounded px-2 py-1">Peluang</a>
                        <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="nav-link rounded px-2 py-1">Pendaftaran</a>
                    @endif
                </div>
            </div>
        @endauth
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
                <span class="font-medium">✓</span> {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                <span class="font-medium">⚠</span> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
