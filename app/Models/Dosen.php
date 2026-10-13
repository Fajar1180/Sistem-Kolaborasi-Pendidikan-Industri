<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = ['user_id', 'nidn', 'prodi'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'dosen_pembimbing_id');
    }

    public function bimbingans(): HasMany
    {
        return $this->hasMany(Bimbingan::class);
    }

    /**
     * S2-01 — Pilih dosen pembimbing untuk sebuah pengajuan: utamakan dosen
     * dengan prodi sama, lalu beban bimbingan paling sedikit. Null bila belum
     * ada data dosen.
     */
    public static function pilihPembimbing(?string $prodi): ?self
    {
        $beban = fn ($q) => $q->whereNotIn('status', [
            Pendaftaran::STATUS_DITOLAK, Pendaftaran::STATUS_SELESAI,
        ]);

        if ($prodi) {
            $dosen = static::query()
                ->where('prodi', $prodi)
                ->withCount(['pendaftarans as beban_bimbingan' => $beban])
                ->orderBy('beban_bimbingan')
                ->orderBy('id')
                ->first();

            if ($dosen) {
                return $dosen;
            }
        }

        return static::query()
            ->withCount(['pendaftarans as beban_bimbingan' => $beban])
            ->orderBy('beban_bimbingan')
            ->orderBy('id')
            ->first();
    }
}
