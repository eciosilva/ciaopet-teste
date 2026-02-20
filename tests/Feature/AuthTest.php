<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration with valid data.
     */
    public function test_user_can_register_with_valid_data()
    {
        $userData = [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'token',
                    'token_type'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Usuário registrado com sucesso!',
                'data' => [
                    'user' => [
                        'name' => 'João Silva',
                        'email' => 'joao@teste.com'
                    ],
                    'token_type' => 'Bearer'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'João Silva',
            'email' => 'joao@teste.com'
        ]);
    }

    /**
     * Test user registration with duplicate email fails.
     */
    public function test_user_cannot_register_with_duplicate_email()
    {
        User::factory()->create(['email' => 'joao@teste.com']);

        $userData = [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Test user registration with invalid data fails.
     */
    public function test_user_registration_validation_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test user can login with correct credentials.
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'joao@teste.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'joao@teste.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'token',
                    'token_type'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'token_type' => 'Bearer'
                ]
            ]);
    }

    /**
     * Test user cannot login with incorrect credentials.
     */
    public function test_user_cannot_login_with_incorrect_credentials()
    {
        User::factory()->create([
            'email' => 'joao@teste.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'joao@teste.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Test authenticated user can access me endpoint.
     */
    public function test_authenticated_user_can_access_me_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]
            ]);
    }

    /**
     * Test unauthenticated user cannot access me endpoint.
     */
    public function test_unauthenticated_user_cannot_access_me_endpoint()
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /**
     * Test authenticated user can logout.
     */
    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Logout realizado com sucesso!'
            ]);
    }

    /**
     * Test unauthenticated user cannot logout.
     */
    public function test_unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /**
     * Test protected routes require authentication.
     */
    public function test_protected_routes_require_authentication()
    {
        $endpoints = [
            ['GET', '/api/pets'],
            ['POST', '/api/pets'],
            ['GET', '/api/pets/1'],
            ['PUT', '/api/pets/1'],
            ['DELETE', '/api/pets/1'],
            ['GET', '/api/pets/options'],
        ];

        foreach ($endpoints as [$method, $url]) {
            $response = $this->json($method, $url);
            
            $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                ->assertJson([
                    'message' => 'Unauthenticated.'
                ]);
        }
    }
}