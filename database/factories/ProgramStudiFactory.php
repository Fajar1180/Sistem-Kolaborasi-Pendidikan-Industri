<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramStudi>
 */
class ProgramStudiFactory extends Factory
{
    protected $model = ProgramStudi::class;

    private static int $urut = 0;

    public function definition(): array
    {
        $urut = ++self::$urut;

        return [
            'kode' => 'PS'.str_pad((string) $urut, 3, '0', STR_PAD_LEFT),
            'nama' => 'Program Studi '.$urut,
            'jenjang' => fake()->randomElement(ProgramStudi::JENJANG),
            'is_aktif' => true,
        ];
    }
}
