<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\WeightLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_weight_logs()
    {
        $user = User::factory()->create();
        WeightLog::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->weightLogs);
        $this->assertInstanceOf(WeightLog::class, $user->weightLogs->first());
    }

    public function test_user_has_one_health_data()
    {
        $user = User::factory()->create();
        $healthData = $user->healthData()->create([
            'height' => 170,
            'weight_goal' => 'lose',
        ]);

        $this->assertEquals($healthData->id, $user->healthData->id);
        $this->assertEquals(170, $user->healthData->height);
    }
}
