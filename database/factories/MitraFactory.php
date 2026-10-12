<?php

namespace Database\Factories;

use App\Models\Mitra;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mitra>
 */
class MitraFactory extends Factory
{
    protected $model = Mitra::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->mitra(),
            'nama_perusahaan' => fake()->company(),
            'alamat' => fake()->address(),
            'kontak' => fake()->numerify('08##########'),
            'deskripsi' => fake()->paragraph(),
            'logo_path' => null,
        ];
    }
}
