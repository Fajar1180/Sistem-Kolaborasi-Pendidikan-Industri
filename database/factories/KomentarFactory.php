<?php

namespace Database\Factories;

use App\Models\Komentar;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Komentar>
 */
class KomentarFactory extends Factory
{
    protected $model = Komentar::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'user_id' => User::factory(),
            'isi' => fake()->paragraph(),
        ];
    }
}
