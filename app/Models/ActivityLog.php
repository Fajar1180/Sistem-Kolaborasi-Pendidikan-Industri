<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id', 'aksi', 'catatan', 'entitas', 'entitas_id', 'waktu',
    ];

    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aksi penting (siapa, apa, kapan — NFR-06).
     */
    public static function catat(string $aksi, ?Model $entitas = null, ?string $catatan = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'catatan' => $catatan,
            'entitas' => $entitas ? $entitas::class : null,
            'entitas_id' => $entitas?->getKey(),
            'waktu' => now(),
        ]);
    }
}
