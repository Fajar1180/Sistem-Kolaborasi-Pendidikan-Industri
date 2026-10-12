<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'aksi' => fake()->randomElement(['create', 'update', 'delete', 'approve']),
            'entitas' => fake()->randomElement(['pendaftaran', 'program', 'user']),
            'entitas_id' => fake()->numberBetween(1, 20),
            'waktu' => now(),
        ];
    }
}
