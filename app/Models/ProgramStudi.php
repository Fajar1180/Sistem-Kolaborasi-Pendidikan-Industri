<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studis';

    public const JENJANG = ['D3', 'D4', 'S1', 'S2'];

    protected $fillable = [
        'kode', 'nama', 'jenjang', 'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }
}
