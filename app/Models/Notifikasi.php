<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id', 'judul', 'isi', 'link', 'dibaca_at',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }

    /**
     * S2-01 — Buat notifikasi dalam sistem untuk seorang pengguna.
     * (S2-07 menyempurnakan daftar/penanda baca + email.)
     */
    public static function kirim(User $user, string $judul, string $isi, ?string $link = null): self
    {
        return static::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'isi' => $isi,
            'link' => $link,
        ]);
    }
}
