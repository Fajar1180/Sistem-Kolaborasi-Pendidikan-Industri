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

    /**
     * Status yang sudah dikomitmenkan — pendaftaran tidak dapat dibatalkan sendiri.
     */
    public const STATUS_TERKOMIT = [
        self::STATUS_DITERIMA_MITRA, self::STATUS_BERJALAN, self::STATUS_SELESAI,
    ];

    /**
     * Status yang menunggu keputusan dosen pembimbing (§7.5) — S2-01.
     */
    public const STATUS_DIPUTUS_DOSEN = [self::STATUS_DIAJUKAN, self::STATUS_PERLU_REVISI];

    /**
     * Status yang menunggu seleksi mitra (§7.5) — S2-02.
     */
    public const STATUS_SELEKSI_MITRA = [self::STATUS_DISETUJUI_DOSEN];

    protected $fillable = [
        'mahasiswa_id', 'program_id', 'dosen_pembimbing_id', 'status', 'catatan',
        'catatan_mahasiswa',
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

    public function komentars(): HasMany
    {
        return $this->hasMany(Komentar::class);
    }

    /**
     * Label status berbahasa Indonesia (badge §7.5).
     */
    public function labelStatus(): string
    {
        return match ($this->status) {
            self::STATUS_DRAF => 'Draf',
            self::STATUS_DIAJUKAN => 'Diajukan',
            self::STATUS_PERLU_REVISI => 'Perlu Revisi',
            self::STATUS_DISETUJUI_DOSEN => 'Disetujui Dosen',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DITERIMA_MITRA => 'Diterima Mitra',
            self::STATUS_BERJALAN => 'Berjalan',
            self::STATUS_SELESAI => 'Selesai',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Boleh dibatalkan mahasiswa? Tidak bila sudah diterima/berjalan/selesai.
     */
    public function bisaDibatalkan(): bool
    {
        return ! in_array($this->status, self::STATUS_TERKOMIT, true);
    }

    /**
     * Boleh diedit mahasiswa? Hanya saat draf atau perlu revisi (§7.5).
     */
    public function bisaDieditMahasiswa(): bool
    {
        return in_array($this->status, [self::STATUS_DRAF, self::STATUS_PERLU_REVISI], true);
    }

    /**
     * Menunggu keputusan dosen pembimbing? (S2-01)
     */
    public function bisaDiputusDosen(): bool
    {
        return in_array($this->status, self::STATUS_DIPUTUS_DOSEN, true);
    }

    /**
     * Menunggu seleksi mitra? (S2-02)
     */
    public function bisaDiseleksiMitra(): bool
    {
        return in_array($this->status, self::STATUS_SELEKSI_MITRA, true);
    }

    /**
     * Berstatus berjalan? (S2-03)
     */
    public function isBerjalan(): bool
    {
        return $this->status === self::STATUS_BERJALAN;
    }
}
