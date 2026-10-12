<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dokumen>
 */
class DokumenFactory extends Factory
{
    protected $model = Dokumen::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'nama' => fake()->word().'.pdf',
            'path' => 'dokumen/'.fake()->uuid().'.pdf',
            'tipe' => 'application/pdf',
            'ukuran' => fake()->numberBetween(50_000, 5_000_000),
        ];
    }
}
