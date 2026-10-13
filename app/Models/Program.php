<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    public const JENIS = [
        'kerja_praktik', 'magang', 'proyek_kolaborasi', 'kunjungan_industri', 'sertifikasi',
    ];

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'mitra_id', 'judul', 'jenis', 'deskripsi', 'syarat', 'kuota',
        'tanggal_mulai', 'tanggal_selesai', 'status_publikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'kuota' => 'integer',
        ];
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class);
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status_publikasi', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status_publikasi === self::STATUS_PUBLISHED;
    }

    /**
     * Kuota terpakai = pendaftaran yang sudah diterima mitra / berjalan / selesai.
     */
    public function kuotaTerpakai(): int
    {
        return $this->pendaftarans()
            ->whereIn('status', ['diterima_mitra', 'berjalan', 'selesai'])
            ->count();
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota - $this->kuotaTerpakai());
    }
}
