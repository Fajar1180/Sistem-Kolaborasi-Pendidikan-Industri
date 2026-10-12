<?php

namespace Database\Factories;

use App\Models\PeriodeAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PeriodeAkademik>
 */
class PeriodeAkademikFactory extends Factory
{
    protected $model = PeriodeAkademik::class;

    private static int $urut = 0;

    public function definition(): array
    {
        $urut = ++self::$urut;
        $tahun = 2020 + intdiv($urut - 1, 2);

        return [
            'kode' => $tahun.'/'.($tahun + 1).'-'.(($urut % 2) + 1),
            'nama' => $tahun.'/'.($tahun + 1).' '.($urut % 2 ? 'Ganjil' : 'Genap'),
            'tanggal_mulai' => now()->startOfMonth(),
            'tanggal_selesai' => now()->addMonths(6),
            'is_aktif' => false,
        ];
    }
}
