<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use App\Models\PenilaianDosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenilaianDosen>
 */
class PenilaianDosenFactory extends Factory
{
    protected $model = PenilaianDosen::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'komponen' => fake()->randomElement(['Sikap', 'Kehadiran', 'Laporan Akhir']),
            'nilai' => fake()->numberBetween(60, 100),
        ];
    }
}
