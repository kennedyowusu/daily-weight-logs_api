<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeightLog>
 */
class WeightLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $timeOfDay = $this->faker->randomElement(['morning', 'evening']);
        $loggedAt = $timeOfDay === 'morning' ? $this->faker->dateTimeBetween('today 00:00', 'today 12:00') : $this->faker->dateTimeBetween('today 12:00', 'today 23:59');

        return [
            'user_id' => User::factory(),
            'weight' => $this->faker->randomFloat(2, 40, 150),
            'time_of_day' => $timeOfDay,
            'logged_at' => $loggedAt,
            'bmi' => null,
        ];
    }
}
