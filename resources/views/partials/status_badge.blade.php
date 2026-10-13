@php
    $warna = match ($pendaftaran->status) {
        'draf' => 'bg-slate-100 text-slate-700',
        'diajukan' => 'bg-blue-100 text-blue-700',
        'perlu_revisi' => 'bg-amber-100 text-amber-800',
        'disetujui_dosen' => 'bg-indigo-100 text-indigo-700',
        'ditolak' => 'bg-red-100 text-red-700',
        'diterima_mitra' => 'bg-emerald-100 text-emerald-700',
        'berjalan' => 'bg-teal-100 text-teal-700',
        'selesai' => 'bg-green-100 text-green-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $warna }}">
    {{ $pendaftaran->labelStatus() }}
</span>
