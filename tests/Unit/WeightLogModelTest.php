<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\WeightLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeightLogModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_bmi_with_valid_health_data()
    {
        $user = User::factory()->create();
        $user->healthData()->create(['height' => 180]);

        $weightLog = WeightLog::factory()->create([
            'user_id' => $user->id,
            'weight' => 75,
        ]);

        $this->assertEquals(23.15, $weightLog->bmi);
    }

    public function test_bmi_is_null_if_no_health_data()
    {
        $user = User::factory()->create();

        $weightLog = WeightLog::factory()->create([
            'user_id' => $user->id,
            'weight' => 75,
        ]);

        $this->assertNull($weightLog->bmi);
    }
}
