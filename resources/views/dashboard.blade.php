@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Selamat datang, {{ $user->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">Anda masuk sebagai <strong>{{ $roleLabel }}</strong>.</p>
    </div>

    {{-- Statistik Real-time --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @if ($user->role === 'admin')
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-sm">
                <p class="text-sm text-blue-700">Total Pengguna</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['totalUsers'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-5 shadow-sm">
                <p class="text-sm text-green-700">Total Program</p>
                <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['totalProgram'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 p-5 shadow-sm">
                <p class="text-sm text-purple-700">Sedang Berjalan</p>
                <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['pendaftaranBerjalan'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-amber-50 to-amber-100 p-5 shadow-sm">
                <p class="text-sm text-amber-700">Menunggu Review</p>
                <p class="mt-2 text-3xl font-bold text-amber-900">{{ $stats['menungguReview'] }}</p>
            </div>
        @elseif ($user->role === 'dosen')
            <div class="rounded-lg bg-gradient-to-br from-red-50 to-red-100 p-5 shadow-sm">
                <p class="text-sm text-red-700">Pengajuan Menunggu</p>
                <p class="mt-2 text-3xl font-bold text-red-900">{{ $stats['pengajuanMenunggu'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-sm">
                <p class="text-sm text-blue-700">Mahasiswa Bimbingan</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['mahasiswaBimbingan'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-slate-50 to-slate-100 p-5 shadow-sm">
                <p class="text-sm text-slate-700">Total Pendaftaran</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalPendaftaran'] }}</p>
            </div>
        @elseif ($user->role === 'mitra')
            <div class="rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-5 shadow-sm">
                <p class="text-sm text-green-700">Program Aktif</p>
                <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['programAktif'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-sm">
                <p class="text-sm text-blue-700">Pendaftaran Masuk</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['pendaftaranMasuk'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-amber-50 to-amber-100 p-5 shadow-sm">
                <p class="text-sm text-amber-700">Menunggu Seleksi</p>
                <p class="mt-2 text-3xl font-bold text-amber-900">{{ $stats['menungguSeleksi'] }}</p>
            </div>
        @else {{-- Mahasiswa --}}
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-sm">
                <p class="text-sm text-blue-700">Total Pendaftaran</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['totalPendaftaran'] }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-5 shadow-sm">
                <p class="text-sm text-green-700">Status Terbaru</p>
                <p class="mt-2 text-lg font-semibold text-green-900 capitalize">{{ str_replace('_', ' ', $stats['statusTerbaru']) }}</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-amber-50 to-amber-100 p-5 shadow-sm">
                <p class="text-sm text-amber-700">Menunggu Diajukan</p>
                <p class="mt-2 text-3xl font-bold text-amber-900">{{ $stats['menungguDiajukan'] }}</p>
            </div>
        @endif
        
        {{-- Notifikasi untuk semua role --}}
        <a href="{{ route('notifikasi.index') }}" class="rounded-lg bg-gradient-to-br from-red-50 to-red-100 p-5 shadow-sm hover:shadow">
            <p class="text-sm text-red-700">Notifikasi Belum Dibaca</p>
            <p class="mt-2 text-3xl font-bold text-red-900">{{ $stats['notifikasiBlmDibaca'] }}</p>
        </a>
    </div>

    {{-- Quick Links (tidak duplikat dengan navbar) --}}
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Akses Cepat</h3>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @if ($user->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-blue-500">
                    <h4 class="font-medium text-blue-900">👥 Pengelolaan Pengguna</h4>
                    <p class="mt-1 text-xs text-slate-500">Tambah, ubah, nonaktifkan akun</p>
                </a>
                <a href="{{ route('admin.rekap.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-green-500">
                    <h4 class="font-medium text-green-900">📊 Rekap & Laporan</h4>
                    <p class="mt-1 text-xs text-slate-500">Statistik, ekspor Excel</p>
                </a>
                <a href="{{ route('admin.import.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-purple-500">
                    <h4 class="font-medium text-purple-900">📂 Impor Data CSV</h4>
                    <p class="mt-1 text-xs text-slate-500">Bulk import mahasiswa/dosen/mitra</p>
                </a>
            @elseif ($user->role === 'dosen')
                <a href="{{ route('dosen.pengajuan.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-red-500">
                    <h4 class="font-medium text-red-900">📋 Persetujuan Pengajuan</h4>
                    <p class="mt-1 text-xs text-slate-500">Setujui / revisi pengajuan mahasiswa</p>
                </a>
                <a href="{{ route('dosen.bimbingan.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-blue-500">
                    <h4 class="font-medium text-blue-900">👨‍🎓 Bimbingan Mahasiswa</h4>
                    <p class="mt-1 text-xs text-slate-500">Pantau progress, berikan feedback</p>
                </a>
            @elseif ($user->role === 'mitra')
                <a href="{{ route('mitra.program.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-green-500">
                    <h4 class="font-medium text-green-900">💼 Peluang Saya</h4>
                    <p class="mt-1 text-xs text-slate-500">Buat, ubah, publikasikan program</p>
                </a>
                <a href="{{ route('mitra.penilaian.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-purple-500">
                    <h4 class="font-medium text-purple-900">⭐ Penilaian Peserta</h4>
                    <p class="mt-1 text-xs text-slate-500">Nilai capaian mahasiswa</p>
                </a>
            @else {{-- Mahasiswa --}}
                <a href="{{ route('mahasiswa.program.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-blue-500">
                    <h4 class="font-medium text-blue-900">🔍 Cari Peluang Program</h4>
                    <p class="mt-1 text-xs text-slate-500">Jelajahi magang, KP, kolaborasi</p>
                </a>
                <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="rounded-lg bg-white p-4 shadow-sm hover:shadow border-l-4 border-green-500">
                    <h4 class="font-medium text-green-900">📝 Pendaftaran Saya</h4>
                    <p class="mt-1 text-xs text-slate-500">Status pengajuan & dokumen</p>
                </a>
            @endif
        </div>
    </div>
@endsection
