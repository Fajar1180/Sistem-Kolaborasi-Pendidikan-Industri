<?php

namespace Database\Factories;

use App\Models\Mitra;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('+1 week', '+1 month');

        return [
            'mitra_id' => Mitra::factory(),
            'judul' => fake()->sentence(4),
            'jenis' => fake()->randomElement(Program::JENIS),
            'deskripsi' => fake()->paragraph(),
            'syarat' => fake()->sentence(),
            'kuota' => fake()->numberBetween(1, 10),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => (clone $mulai)->modify('+3 months'),
            'status_publikasi' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status_publikasi' => 'draft']);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => ['status_publikasi' => 'closed']);
    }
}
