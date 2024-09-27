<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function it_can_login_successfully()
    {
        $user = User::factory()->create([
            'password' => Hash::make('your_password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'your_password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'user_type'],
            ]);
    }

    public function it_returns_unauthorized_for_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('your_password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid email or password.',
            ]);
    }

    public function it_returns_error_when_email_not_found()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'notfound@example.com',
            'password' => 'some_password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Email not found.',
            ]);
    }

    public function it_can_register_successfully()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'your_password',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'user_type'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function it_returns_error_when_email_not_unique()
    {
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'john@example.com',
            'password' => 'another_password',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The email has already been taken.',
                'errors' => [
                    'email' => [
                        'The email has already been taken.',
                    ],
                ],
            ]);
    }
}
