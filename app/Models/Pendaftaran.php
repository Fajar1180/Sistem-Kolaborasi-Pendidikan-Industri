<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    public const STATUS_DRAF = 'draf';
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_PERLU_REVISI = 'perlu_revisi';
    public const STATUS_DISETUJUI_DOSEN = 'disetujui_dosen';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DITERIMA_MITRA = 'diterima_mitra';
    public const STATUS_BERJALAN = 'berjalan';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'mahasiswa_id', 'program_id', 'dosen_pembimbing_id', 'status', 'catatan',
        'diajukan_at', 'disetujui_dosen_at', 'diterima_mitra_at', 'selesai_at',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_at' => 'datetime',
            'disetujui_dosen_at' => 'datetime',
            'diterima_mitra_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function bimbingans(): HasMany
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function penilaianDosens(): HasMany
    {
        return $this->hasMany(PenilaianDosen::class);
    }

    public function penilaianMitras(): HasMany
    {
        return $this->hasMany(PenilaianMitra::class);
    }
}
