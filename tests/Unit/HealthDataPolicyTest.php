<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\HealthData;
use App\Policies\HealthDataPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthDataPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_health_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $healthData = HealthData::factory()->create(['user_id' => $user->id]);

        $policy = new HealthDataPolicy();

        $this->assertTrue($policy->view($admin, $healthData));
    }

    public function test_user_can_view_their_own_health_data()
    {
        $user = User::factory()->create(['role' => 'user']);
        $healthData = HealthData::factory()->create(['user_id' => $user->id]);

        $policy = new HealthDataPolicy();

        $this->assertTrue($policy->view($user, $healthData));
    }

    public function test_user_cannot_view_other_users_health_data()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $healthData = HealthData::factory()->create(['user_id' => $otherUser->id]);

        $policy = new HealthDataPolicy();

        $this->assertFalse($policy->view($user, $healthData));
    }
}
