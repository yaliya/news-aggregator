<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_preferences(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'preferences' => [
                'sources' => ['newsapi', 'guardian'],
                'categories' => ['technology'],
            ],
        ];

        $this->withExceptionHandling();

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertCreated()
            ->assertJsonStructure(['access_token', 'token_type']);
    }
}

