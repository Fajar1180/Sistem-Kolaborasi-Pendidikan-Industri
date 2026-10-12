@extends('layouts.app')

@section('title', $judul ?? 'Halaman')

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">{{ $judul ?? 'Halaman' }}</h2>
        <p class="mt-1 text-sm text-slate-500">
            Halaman ini masih berupa placeholder dan akan diisi pada item <strong>{{ $ref ?? '-' }}</strong>.
        </p>
        <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-sm text-blue-600 hover:underline">Kembali ke dashboard</a>
    </div>
@endsection
