<?php

namespace Database\Factories;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notifikasi>
 */
class NotifikasiFactory extends Factory
{
    protected $model = Notifikasi::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'judul' => fake('id_ID')->sentence(3),
            'isi' => fake('id_ID')->paragraph(),
            'link' => '/dashboard',
            'dibaca_at' => null,
        ];
    }
}
