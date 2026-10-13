@extends('layouts.app')

@section('title', 'Buat Peluang')

@section('content')
    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Buat Peluang Baru</h2>
        <p class="mt-1 text-sm text-slate-500">Peluang disimpan sebagai <strong>draf</strong>. Publikasikan dari daftar agar terlihat mahasiswa.</p>

        <form method="POST" action="{{ route('mitra.program.store') }}" class="mt-6 space-y-4">
            @include('mitra.program._form')
        </form>
    </div>
@endsection
