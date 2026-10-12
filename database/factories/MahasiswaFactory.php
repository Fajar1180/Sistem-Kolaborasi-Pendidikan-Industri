<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->mahasiswa(),
            'nim' => fake()->unique()->numerify('2024#######'),
            'prodi' => fake()->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'angkatan' => fake()->numberBetween(2022, 2025),
            'no_hp' => fake()->numerify('08##########'),
        ];
    }
}
