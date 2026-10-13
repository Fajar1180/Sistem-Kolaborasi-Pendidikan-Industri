@extends('layouts.app')

@section('title', 'Peluang Program')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-xl font-semibold">Peluang Program</h2>
        <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="text-sm text-blue-600 hover:underline">Pendaftaran Saya</a>
    </div>

    <form method="GET" class="mt-4 grid gap-3 rounded-lg bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-2">
            <label for="q" class="text-xs font-medium text-slate-600">Cari judul / mitra</label>
            <input id="q" type="text" name="q" value="{{ $q }}" placeholder="Ketik kata kunci…"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="jenis" class="text-xs font-medium text-slate-600">Jenis</label>
            <select id="jenis" name="jenis" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($jenisTersedia as $j)
                    <option value="{{ $j }}" @selected($j === $jenisTerpilih)>{{ ucfirst(str_replace('_', ' ', $j)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="periode" class="text-xs font-medium text-slate-600">Periode</label>
            @php
                $namaBulan = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
                              '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
            @endphp
            <select id="periode" name="periode" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($periodeTersedia as $p)
                    @php [$tahun, $bulan] = explode('-', $p); @endphp
                    <option value="{{ $p }}" @selected($p === $periodeTerpilih)>{{ ($namaBulan[$bulan] ?? $bulan).' '.$tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 lg:col-span-4">
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('mahasiswa.program.index') }}" class="px-3 py-2 text-sm text-slate-600 hover:underline">Reset</a>
        </div>
    </form>

    @forelse ($programs as $program)
        <a href="{{ route('mahasiswa.program.show', $program) }}"
           class="mt-3 block rounded-lg bg-white p-5 shadow-sm hover:shadow">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="font-medium text-slate-900">{{ $program->judul }}</h3>
                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ ucfirst(str_replace('_', ' ', $program->jenis)) }}</span>
            </div>
            <p class="mt-1 text-sm text-slate-500">{{ $program->mitra?->nama_perusahaan ?? 'Mitra' }}</p>
            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($program->deskripsi, 160) }}</p>
            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500">
                <span>Periode: {{ $program->tanggal_mulai->format('d/m/Y') }} – {{ $program->tanggal_selesai->format('d/m/Y') }}</span>
                <span>Sisa kuota: <strong class="text-slate-700">{{ max(0, $program->kuota - $program->kuota_terpakai) }}</strong></span>
            </div>
        </a>
    @empty
        <div class="mt-4 rounded-lg bg-white p-6 text-center text-sm text-slate-500 shadow-sm">
            Belum ada peluang yang cocok. Coba ubah kata kunci atau filter.
        </div>
    @endforelse

    <div class="mt-4">{{ $programs->links() }}</div>
@endsection
