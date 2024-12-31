<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\WeightLog;
use Tests\TestCase;

class WeightLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_weight_log()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/weight-logs', [
                'weight' => 75.5,
                'time_of_day' => 'morning',
                'logged_at' => now()->setTime(9, 0),
            ]);

        $response->assertStatus(201);

        $response->assertStatus(201);
        $this->assertDatabaseHas('weight_logs', [
            'weight' => 75.5,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_their_weight_logs()
    {
        $user = User::factory()->create();
        WeightLog::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/weight-logs');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_their_weight_log()
    {
        $user = User::factory()->create(['role' => 'user']);
        $weightLog = WeightLog::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/weight-logs/{$weightLog->id}", [
                'weight' => 80.0,
                'time_of_day' => 'evening',
                'logged_at' => now()->setTime(18, 0),
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('weight_logs', [
            'id' => $weightLog->id,
            'weight' => 80.0,
        ]);
    }

    public function test_user_can_delete_their_weight_log()
    {
        $user = User::factory()->create();
        $weightLog = WeightLog::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/weight-logs/{$weightLog->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('weight_logs', ['id' => $weightLog->id]);
    }

    public function test_user_can_filter_weight_logs_by_time_of_day()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user, 'sanctum');

        // Create logs for morning and evening
        WeightLog::factory()->create(['user_id' => $user->id, 'time_of_day' => 'morning']);
        WeightLog::factory()->create(['user_id' => $user->id, 'time_of_day' => 'evening']);

        // Filter by morning logs
        $response = $this->getJson('/api/v1/weight-logs?time_of_day=morning');
        $response->assertStatus(200)
            ->assertJsonFragment(['time_of_day' => 'Morning']);

        // Filter by evening logs
        $response = $this->getJson('/api/v1/weight-logs?time_of_day=evening');
        $response->assertStatus(200)
            ->assertJsonFragment(['time_of_day' => 'Evening']);
    }

}
