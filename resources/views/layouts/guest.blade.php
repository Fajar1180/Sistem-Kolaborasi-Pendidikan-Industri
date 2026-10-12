<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 antialiased">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow">
        <h1 class="mb-1 text-lg font-semibold">{{ config('app.name') }}</h1>
        <p class="mb-6 text-sm text-slate-500">@yield('subtitle', 'Sistem Informasi Kolaborasi Pendidikan dan Industri')</p>
        @yield('content')
    </div>
</body>
</html>
