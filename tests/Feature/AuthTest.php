<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_return_valid_json_with_token(): void
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->post('/api/auth/register', $payload);

        $response
            ->assertCreated()
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_it_fail_when_receive_invalid_data(): void
    {
        $payload = [
            'email' => 'test',
            'password' => 'pas',
            'password_confirmation' => 'password'
        ];

        $response = $this->post('/api/auth/register', $payload);

        $response
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_it_can_logout_user(): void
    {

        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->post('/api/auth/logout');

        $response
            ->assertNoContent();
    }
}
