<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'height' => $this->faker->randomFloat(2, 1.5, 2),
            'weight_goal' => $this->faker->randomElement(['gain', 'lose', 'maintain']),
        ];
    }
}
