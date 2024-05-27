<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_user_to_register_with_valid_data()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => ['id']
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function it_prevents_registration_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_name_email_password_and_password_confirm()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirm']);
    }

    /** @test */
    public function it_requires_password_confirmation_to_match_password()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'differentpassword',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password_confirm']);
    }
    /** @test */
    public function it_requires_minimum_length_to_name_and_password()
    {
        $data = [
            'name' => 'T',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirm' => '123',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name','password']);
    }
    /** @test */
    public function it_requires_maximum_length_to_name_and_password()
    {
        $data = [
            'name' => 'abcdefhijklmnopqrstuvwzyxabcdefhijklmnopqrstuvwzyxabcdefhijklmnopqrstuvwzyxabcdefhijklmnopqrstuvwzyxabcdefhijklmnopqrstuvwzyx',
            'email' => 'test@example.com',
            'password' => 'abcdefhijklmnopqrstuvwzyx',
            'password_confirm' => 'abcdefhijklmnopqrstuvwzyx',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name','password']);
    }
    /** @test */
    public function it_requires_email_formating_to_email()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test_example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
        /** @test */
        public function it_validates_password_with_hashed_password()
        {
            $data = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirm' => 'password123',
            ];

            $response = $this->postJson('/api/login', $data);

            $response->assertStatus(201);

            $user = User::where('email', 'test@example.com')->first();
            $this->assertNotNull($user);
            $this->assertTrue(Hash::check('password123', $user->password));

            // Ensure the password is stored as a hash and not in plain text
            $this->assertNotEquals('password123', $user->password);
        }
}
