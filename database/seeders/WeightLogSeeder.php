<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeightLog;
use Illuminate\Database\Seeder;

class WeightLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            if ($user->weightLogs()->count() === 0) {
                WeightLog::factory()->count(10)->for($user)->create();
            }
        });
    }
}
