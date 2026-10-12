@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Selamat datang, {{ $user->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">Anda masuk sebagai <strong>{{ $roleLabel }}</strong>.</p>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @if ($user->role === 'admin')
            <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-medium">Pengelolaan Pengguna</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola akun & data master (S1-06).</p>
            </a>
        @elseif ($user->role === 'dosen')
            <a href="{{ route('dosen.pengajuan.index') }}" class="rounded-lg bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-medium">Persetujuan Pengajuan</h3>
                <p class="mt-1 text-sm text-slate-500">Setujui / revisi pengajuan mahasiswa (S2-01).</p>
            </a>
        @elseif ($user->role === 'mitra')
            <a href="{{ route('mitra.program.index') }}" class="rounded-lg bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-medium">Peluang Program</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola profil & publikasi program (S1-07).</p>
            </a>
        @else
            <a href="{{ route('mahasiswa.program.index') }}" class="rounded-lg bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-medium">Peluang Program</h3>
                <p class="mt-1 text-sm text-slate-500">Lihat peluang & daftar program (S1-08).</p>
            </a>
        @endif
    </div>
@endsection
