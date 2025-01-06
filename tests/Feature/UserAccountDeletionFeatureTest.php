<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserAccountDeletionFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can delete their account.
     */
    public function test_authenticated_user_can_delete_their_account()
    {
        $user = User::factory()->hasWeightLogs(3)->hasHealthData()->create();

        $this->actingAs($user)
            ->deleteJson('/api/v1/profile')
            ->assertStatus(200)
            ->assertJson(['message' => 'User deleted successfully.']);

        // Assert that the user no longer exists in the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // Assert related data is deleted
        $this->assertDatabaseMissing('weight_logs', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('health_data', ['user_id' => $user->id]);
    }

    /**
     * Test unauthorized user cannot delete another user's account.
     */
    public function test_user_cannot_delete_another_users_account()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson('/api/v1/profile')
            ->assertStatus(403);

        // Assert that the other user still exists in the database
        $this->assertDatabaseHas('users', ['id' => $otherUser->id]);
    }

    /**
     * Test deletion logs are created.
     */
    public function test_user_deletion_creates_log_entry()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson('/api/v1/profile')
            ->assertStatus(200);

        // Assert that a deletion log was created
        $this->assertDatabaseHas('deletion_logs', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
