<?php

namespace Database\Factories;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bimbingan>
 */
class BimbinganFactory extends Factory
{
    protected $model = Bimbingan::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'dosen_id' => Dosen::factory(),
            'catatan' => fake()->paragraph(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
