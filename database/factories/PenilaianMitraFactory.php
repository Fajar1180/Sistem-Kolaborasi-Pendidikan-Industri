<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use App\Models\PenilaianMitra;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenilaianMitra>
 */
class PenilaianMitraFactory extends Factory
{
    protected $model = PenilaianMitra::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'komponen' => fake()->randomElement(['Kinerja', 'Disiplin', 'Kerja Sama']),
            'nilai' => fake()->numberBetween(60, 100),
        ];
    }
}
