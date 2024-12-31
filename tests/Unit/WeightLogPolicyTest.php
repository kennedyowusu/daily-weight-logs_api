<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\WeightLog;
use App\Policies\WeightLogPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeightLogPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_any_weight_log()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $policy = new WeightLogPolicy();

        $this->assertTrue($policy->viewAny($admin));
    }

    public function test_user_cannot_view_other_users_weight_log()
    {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);
        $weightLog = WeightLog::factory()->create(['user_id' => $otherUser->id]);
        $policy = new WeightLogPolicy();

        $this->assertFalse($policy->view($user, $weightLog));
    }

    public function test_user_can_view_their_own_weight_log()
    {
        $user = User::factory()->create(['role' => 'user']);
        $weightLog = WeightLog::factory()->create(['user_id' => $user->id]);
        $policy = new WeightLogPolicy();

        $this->assertTrue($policy->view($user, $weightLog));
    }

    public function test_user_can_create_weight_log()
    {
        $user = User::factory()->create(['role' => 'user']);
        $policy = new WeightLogPolicy();

        $this->assertTrue($policy->create($user));
    }

    public function test_admin_can_delete_any_weight_log()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $weightLog = WeightLog::factory()->create();
        $policy = new WeightLogPolicy();

        $this->assertTrue($policy->delete($admin, $weightLog));
    }

    public function test_user_can_delete_their_own_weight_log()
    {
        $user = User::factory()->create();
        $weightLog = WeightLog::factory()->create(['user_id' => $user->id]);
        $policy = new WeightLogPolicy();

        $this->assertTrue($policy->delete($user, $weightLog));
    }
}
