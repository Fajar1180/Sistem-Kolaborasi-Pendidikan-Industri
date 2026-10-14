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
            <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>
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
            @if (auth()->user()->isAdmin())
                <div class="border-t border-slate-200 bg-slate-50">
                    <div class="mx-auto flex max-w-6xl flex-wrap gap-4 px-4 py-2 text-sm">
                        <a href="{{ route('admin.users.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Pengguna</a>
                        <a href="{{ route('admin.master.program-studi.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Program Studi</a>
                        <a href="{{ route('admin.master.periode-akademik.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Periode Akademik</a>
                        <a href="{{ route('admin.import.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Impor CSV</a>
                        <a href="{{ route('admin.pengajuan.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Pengajuan Masuk</a>
                        <a href="{{ route('admin.rekap.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Rekap & Laporan</a>
                    </div>
                </div>
            @elseif (auth()->user()->isDosen())
                <div class="border-t border-slate-200 bg-slate-50">
                    <div class="mx-auto flex max-w-6xl flex-wrap gap-4 px-4 py-2 text-sm">
                        <a href="{{ route('dosen.pengajuan.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Pengajuan Masuk</a>
                        <a href="{{ route('dosen.bimbingan.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Bimbingan</a>
                    </div>
                </div>
            @elseif (auth()->user()->isMitra())
                <div class="border-t border-slate-200 bg-slate-50">
                    <div class="mx-auto flex max-w-6xl flex-wrap gap-4 px-4 py-2 text-sm">
                        <a href="{{ route('mitra.profil.edit') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Profil Perusahaan</a>
                        <a href="{{ route('mitra.program.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Peluang Saya</a>
                        <a href="{{ route('mitra.penilaian.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Penilaian Peserta</a>
                    </div>
                </div>
            @endif
            @if (auth()->user()->isMahasiswa())
                <div class="border-t border-slate-200 bg-slate-50">
                    <div class="mx-auto flex max-w-6xl flex-wrap gap-4 px-4 py-2 text-sm">
                        <a href="{{ route('mahasiswa.program.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Peluang Program</a>
                        <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="text-slate-600 hover:text-blue-600 hover:underline">Pendaftaran Saya</a>
                    </div>
                </div>
            @endif
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
