<?php

namespace Database\Factories;

use App\Models\Laporan;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Laporan>
 */
class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'path' => 'laporan/'.fake()->uuid().'.pdf',
            'tenggat' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'terlambat' => fake()->boolean(20),
            'status_tinjau' => 'menunggu',
        ];
    }
}
