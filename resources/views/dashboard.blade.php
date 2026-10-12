@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Selamat datang, {{ $user->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">Anda masuk sebagai <strong>{{ $roleLabel }}</strong>.</p>
    </div>
@endsection
