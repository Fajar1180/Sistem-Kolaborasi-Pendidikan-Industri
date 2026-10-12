<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kegiatan>
 */
class KegiatanFactory extends Factory
{
    protected $model = Kegiatan::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'uraian' => fake()->paragraph(),
            'bukti_path' => null,
            'status_tinjau' => 'menunggu',
        ];
    }
}
