<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser($userData): User
    {
        return User::factory(1)->createOne($userData);
    }

    public function test_it_return_valid_json_with_token_in_register_request(): void
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

    public function test_it_fail_when_receive_invalid_data_in_register_request(): void
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

    public function test_it_return_valid_json_with_token_in_login_request(): void
    {
        $userData = [
            'email' => 'test@test.com',
            'password' => 'password'
        ];

        $this->createUser($userData);

        $payload = [
            'email' => $userData['email'],
            'password' => $userData['password'],
        ];

        $response = $this->post('/api/auth/login', $payload);

        $response
            ->assertOk()
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_it_fail_when_receive_invalid_data_in_login_request(): void
    {
        $userData = [
            'email' => 'test@test.com',
            'password' => 'password'
        ];

        $this->createUser($userData);

        $payload = [
            'email' => $userData['email'],
            'password' => 'pas',
        ];

        $response = $this->post('/api/auth/register', $payload);

        $response
            ->assertSessionHasErrors(['email']);
    }

    public function test_it_can_logout_user(): void
    {

        $user = User::factory()->createOne();

        $user->createToken('API');

        $response = $this->actingAs($user)->post('/api/auth/logout');

        $this->assertFalse($user->tokens()->exists());

        $response
            ->assertNoContent();
    }
}
