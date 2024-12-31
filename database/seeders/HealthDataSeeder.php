<?php

namespace Database\Seeders;

use App\Models\HealthData;
use App\Models\User;
use Illuminate\Database\Seeder;

class HealthDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            if (!$user->healthData()) {
                HealthData::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
