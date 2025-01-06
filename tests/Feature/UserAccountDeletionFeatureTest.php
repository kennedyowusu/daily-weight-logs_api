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

            $this->assertDatabaseMissing('users', ['id' => $user->id]);
            $this->assertDatabaseMissing('weight_logs', ['user_id' => $user->id]);
            $this->assertDatabaseMissing('health_data', ['user_id' => $user->id]);
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
