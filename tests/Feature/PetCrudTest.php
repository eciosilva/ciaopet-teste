<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PetCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * Test authenticated user can list pets.
     */
    public function test_authenticated_user_can_list_pets()
    {
        Pet::factory()->count(3)->create();

        $response = $this->getJson('/api/pets');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'nome',
                        'especie',
                        'raca',
                        'genero',
                        'data_nascimento',
                        'idade',
                        'peso',
                        'peso_formatado',
                        'numero_microchip',
                        'observacoes',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'pagination' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page'
                ]
            ])
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Test authenticated user can create pet with valid data.
     */
    public function test_authenticated_user_can_create_pet_with_valid_data()
    {
        $petData = [
            'nome' => 'Rex',
            'especie' => 'Cachorro',
            'raca' => 'Pastor Alemão',
            'genero' => 'Macho',
            'data_nascimento' => '2021-06-10',
            'peso' => 35.5,
            'numero_microchip' => '123456789012345',
            'observacoes' => 'Pet muito protetor e obediente.',
            'tutor_id' => $this->user->id
        ];

        $response = $this->postJson('/api/pets', $petData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'nome',
                    'especie',
                    'raca',
                    'genero',
                    'data_nascimento',
                    'idade',
                    'peso',
                    'peso_formatado',
                    'numero_microchip',
                    'observacoes',
                    'tutor' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Pet criado com sucesso!',
                'data' => [
                    'nome' => 'Rex',
                    'especie' => 'Cachorro',
                    'raca' => 'Pastor Alemão',
                    'genero' => 'Macho',
                    'data_nascimento' => '2021-06-10',
                    'peso_formatado' => '35.50 kg',
                    'numero_microchip' => '123456789012345',
                    'observacoes' => 'Pet muito protetor e obediente.',
                    'tutor' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                        'email' => $this->user->email
                    ]
                ]
            ]);

        $this->assertDatabaseHas('pets', [
            'nome' => 'Rex',
            'especie' => 'Cachorro',
            'numero_microchip' => '123456789012345',
            'tutor_id' => $this->user->id
        ]);
    }

    /**
     * Test pet creation fails with invalid data.
     */
    public function test_pet_creation_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/pets', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['nome', 'especie']);
    }

    /**
     * Test pet creation fails with duplicate microchip.
     */
    public function test_pet_creation_fails_with_duplicate_microchip()
    {
        Pet::factory()->create(['numero_microchip' => '123456789012345']);

        $petData = [
            'nome' => 'Rex',
            'especie' => 'Cachorro',
            'numero_microchip' => '123456789012345'
        ];

        $response = $this->postJson('/api/pets', $petData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('numero_microchip');
    }

    /**
     * Test authenticated user can view specific pet.
     */
    public function test_authenticated_user_can_view_specific_pet()
    {
        $pet = Pet::factory()->create([
            'nome' => 'Bella',
            'tutor_id' => $this->user->id
        ]);

        $response = $this->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $pet->id,
                    'nome' => 'Bella',
                    'tutor' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name
                    ]
                ]
            ]);
    }

    /**
     * Test viewing non-existent pet returns 404.
     */
    public function test_viewing_non_existent_pet_returns_404()
    {
        $response = $this->getJson('/api/pets/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Pet] 999'
            ]);
    }

    /**
     * Test authenticated user can update pet.
     */
    public function test_authenticated_user_can_update_pet()
    {
        $pet = Pet::factory()->create(['nome' => 'Bella Original']);

        $updateData = [
            'nome' => 'Bella Atualizada',
            'especie' => 'Gato',
            'raca' => 'Persa',
            'genero' => 'Fêmea',
            'data_nascimento' => '2022-03-15',
            'peso' => 4.2,
            'observacoes' => 'Gata muito carinhosa.'
        ];

        $response = $this->putJson("/api/pets/{$pet->id}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Pet atualizado com sucesso!',
                'data' => [
                    'id' => $pet->id,
                    'nome' => 'Bella Atualizada',
                    'especie' => 'Gato',
                    'raca' => 'Persa',
                    'genero' => 'Fêmea',
                    'observacoes' => 'Gata muito carinhosa.'
                ]
            ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'nome' => 'Bella Atualizada',
            'especie' => 'Gato'
        ]);
    }

    /**
     * Test authenticated user can delete pet.
     */
    public function test_authenticated_user_can_delete_pet()
    {
        $pet = Pet::factory()->create(['nome' => 'Pet para deletar']);

        $response = $this->deleteJson("/api/pets/{$pet->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Pet removido com sucesso!'
            ]);

        $this->assertSoftDeleted('pets', [
            'id' => $pet->id
        ]);
    }

    /**
     * Test deleting non-existent pet returns 404.
     */
    public function test_deleting_non_existent_pet_returns_404()
    {
        $response = $this->deleteJson('/api/pets/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Pet] 999'
            ]);
    }

    /**
     * Test pets list with filters works.
     */
    public function test_pets_list_with_filters_works()
    {
        Pet::factory()->create(['especie' => 'Cachorro', 'genero' => 'Macho']);
        Pet::factory()->create(['especie' => 'Gato', 'genero' => 'Fêmea']);
        Pet::factory()->create(['especie' => 'Cachorro', 'genero' => 'Fêmea']);

        $response = $this->getJson('/api/pets?especie=Cachorro&genero=Macho');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'pagination' => [
                    'total' => 1
                ]
            ]);
    }

    /**
     * Test pets list with pagination works.
     */
    public function test_pets_list_with_pagination_works()
    {
        Pet::factory()->count(25)->create();

        $response = $this->getJson('/api/pets?page=1&per_page=10');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'pagination' => [
                    'current_page' => 1,
                    'per_page' => 10,
                    'total' => 25,
                    'last_page' => 3
                ]
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    /**
     * Test pets options endpoint works.
     */
    public function test_pets_options_endpoint_works()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/pets/options');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'generos',
                    'especies_comuns'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'generos' => Pet::GENEROS,
                    'especies_comuns' => Pet::ESPECIES_COMUNS,
                ]
            ]);
    }

    /**
     * Test unauthenticated user cannot access pets endpoints.
     */
    public function test_unauthenticated_user_cannot_access_pets_endpoints()
    {
        // Create pet only when needed for specific tests
        $petId = Pet::factory()->create()->id;

        $endpoints = [
            ['GET', '/api/pets'],
            ['POST', '/api/pets', ['nome' => 'Test', 'especie' => 'Cachorro']],
            ['GET', "/api/pets/{$petId}"],
            ['PUT', "/api/pets/{$petId}", ['nome' => 'Updated']],
            ['DELETE', "/api/pets/{$petId}"],
            ['GET', '/api/pets/options'],
        ];

        foreach ($endpoints as $index => $endpoint) {
            // Clear authentication for each request
            $this->app['auth']->forgetGuards();
            
            $method = $endpoint[0];
            $url = $endpoint[1];
            $data = $endpoint[2] ?? [];
            
            $response = $this->json($method, $url, $data);
            
            // Add debug info if test fails
            if ($response->status() !== 401) {
                $this->fail("Endpoint {$method} {$url} (index {$index}) should return 401 but returned {$response->status()}. Response: " . $response->getContent());
            }
            
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        }
    }
}