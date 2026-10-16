@extends('layouts.guest')

@section('title', 'Sistem Kolaborasi Pendidikan & Industri')
@section('subtitle', 'Menghubungkan mahasiswa, dosen, mitra industri, dan admin')

@section('content')
    <div class="text-center max-w-3xl mx-auto">
        <h1 class="text-4xl font-bold text-slate-900 mb-4">
            Sistem Kolaborasi Pendidikan & Industri
        </h1>
        <p class="text-lg text-slate-600 mb-8">
            Platform terpadu untuk mengelola kolaborasi pendidikan dan industri — 
            dari pendaftaran program, pengajuan penempatan, persetujuan, pemantauan, 
            hingga penilaian dan laporan.
        </p>

        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                Masuk ke Akun
            </a>
            <a href="mailto:admin@skp.test" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition">
                Hubungi Admin
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 mb-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Untuk Siapa Aplikasi Ini?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 rounded-lg bg-slate-50">
                    <h3 class="font-bold text-blue-700 mb-2">🎓 Mahasiswa</h3>
                    <p class="text-sm text-slate-600">Cari peluang magang, kerja praktik, dan program kolaborasi dengan industri.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50">
                    <h3 class="font-bold text-green-700 mb-2">👨‍🏫 Dosen</h3>
                    <p class="text-sm text-slate-600">Review pengajuan mahasiswa, monitoring bimbingan, dan penilaian.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50">
                    <h3 class="font-bold text-purple-700 mb-2">🏢 Mitra Industri</h3>
                    <p class="text-sm text-slate-600">Tawarkan program, seleksi peserta, dan nilai capaian mahasiswa.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50">
                    <h3 class="font-bold text-red-700 mb-2">⚙️ Admin</h3>
                    <p class="text-sm text-slate-600">Kelola pengguna, program, periode, dan rekap data terpusat.</p>
                </div>
            </div>
        </div>

        <div class="text-sm text-slate-500">
            <p>Belum punya akun? Hubungi administrator untuk mendapatkan akses.</p>
            <p class="mt-2">Email: <a href="mailto:admin@skp.test" class="text-blue-600 hover:underline">admin@skp.test</a></p>
        </div>
    </div>
@endsection

