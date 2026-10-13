Halo {{ $pendaftaran->mahasiswa?->user?->name ?? 'Peserta' }},

Status pendaftaran Anda (ID: SKPI-{{ $pendaftaran->id }}) telah berubah menjadi: {{ $label }}.

Program: {{ $pendaftaran->program?->judul ?? '-' }}
@if($catatan)
Catatan: {{ $catatan }}
@endif

Silakan masuk ke aplikasi untuk detail lebih lanjut.

Salam,
Sistem Kolaborasi Pendidikan-Industri
