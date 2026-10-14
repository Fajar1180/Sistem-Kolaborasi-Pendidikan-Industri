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
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-3">
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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Keluar</button>
                    </form>
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
                        <a href="{{ route('admin.rekap.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Rekap & Laporan</a>
                    @elseif (auth()->user()->isDosen())
                        <a href="{{ route('dosen.pengajuan.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Pengajuan Masuk</a>
                        <a href="{{ route('dosen.bimbingan.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Bimbingan</a>
                    @elseif (auth()->user()->isMitra())
                        <a href="{{ route('mitra.profil.edit') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Profil Perusahaan</a>
                        <a href="{{ route('mitra.program.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Peluang Saya</a>
                        <a href="{{ route('mitra.penilaian.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Penilaian Peserta</a>
                    @endif
                    @if (auth()->user()->isMahasiswa())
                        <a href="{{ route('mahasiswa.program.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Peluang Program</a>
                        <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-blue-600 md:hover:bg-transparent">Pendaftaran Saya</a>
                    @endif
                </div>
            </div>
        @endauth
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-800">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 px-4 py-2 text-red-800">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 px-4 py-2 text-red-800">
                <ul class="list-inside list-disc text-sm">
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
