<?php

namespace Tests\Unit;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_profile()
    {
        $user = User::factory()->create();
        $policy = new UserPolicy();

        $this->assertTrue($policy->view($user, $user)->allowed());
    }

    public function test_user_cannot_view_other_users_profile()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $policy = new UserPolicy();

        $this->assertFalse($policy->view($user1, $user2)->allowed());
    }

    public function test_admin_can_manage_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $policy = new UserPolicy();

        $this->assertTrue($policy->manage($admin));
    }

    public function test_non_admin_cannot_manage_users()
    {
        $user = User::factory()->create(['role' => 'user']);
        $policy = new UserPolicy();

        $this->assertFalse($policy->manage($user));
    }
}
