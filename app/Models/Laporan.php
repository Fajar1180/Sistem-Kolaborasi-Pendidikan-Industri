<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'pendaftaran_id', 'path', 'tenggat', 'terlambat', 'status_tinjau',
    ];

    protected function casts(): array
    {
        return [
            'tenggat' => 'date',
            'terlambat' => 'boolean',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
