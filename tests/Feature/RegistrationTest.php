<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Kennedy Owusu',
            'username' => 'kennedyowusu',
            'email' => 'kennedyowusu@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'kennedyowusu@example.com',
        ]);
    }

    public function test_registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/register', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'username', 'password', 'email']);
    }
}
