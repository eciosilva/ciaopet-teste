<?php

namespace Tests\Unit;

use App\Models\Pet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pet has correct fillable attributes.
     */
    public function test_pet_has_correct_fillable_attributes()
    {
        $pet = new Pet();
        
        $expectedFillable = [
            'nome',
            'especie',
            'raca',
            'genero',
            'data_nascimento',
            'peso',
            'numero_microchip',
            'observacoes',
            'tutor_id',
        ];
        
        $this->assertEqualsCanonicalizing($expectedFillable, $pet->getFillable());
    }

    /**
     * Test pet uses SoftDeletes trait.
     */
    public function test_pet_uses_soft_deletes_trait()
    {
        $pet = new Pet();
        
        $this->assertContains(SoftDeletes::class, class_uses($pet));
    }

    /**
     * Test pet has correct casts.
     */
    public function test_pet_has_correct_casts()
    {
        $pet = new Pet();
        
        $expectedCasts = [
            'id' => 'int',
            'data_nascimento' => 'date',
            'peso' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
        
        // Get the actual casts and filter to match only our expected ones
        $actualCasts = array_intersect_key($pet->getCasts(), $expectedCasts);
        
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    /**
     * Test pet belongs to user (tutor).
     */
    public function test_pet_belongs_to_user_tutor()
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['tutor_id' => $user->id]);

        $this->assertInstanceOf(User::class, $pet->tutor);
        $this->assertEquals($user->id, $pet->tutor->id);
        $this->assertEquals($user->name, $pet->tutor->name);
    }

    /**
     * Test pet can exist without tutor.
     */
    public function test_pet_can_exist_without_tutor()
    {
        $pet = Pet::factory()->create(['tutor_id' => null]);
        
        $this->assertNull($pet->tutor);
        $this->assertNull($pet->tutor_id);
    }

    /**
     * Test pet idade accessor calculates correct age.
     */
    public function test_pet_idade_accessor_calculates_correct_age()
    {
        // Pet nascido há 3 anos
        $birthDate = Carbon::now()->subYears(3)->subDays(15);
        $pet = Pet::factory()->create([
            'data_nascimento' => $birthDate->format('Y-m-d')
        ]);

        $this->assertEquals(3, $pet->idade);
    }

    /**
     * Test pet idade accessor returns null when no birth date.
     */
    public function test_pet_idade_accessor_returns_null_when_no_birth_date()
    {
        $pet = Pet::factory()->create(['data_nascimento' => null]);
        
        $this->assertNull($pet->idade);
    }

    /**
     * Test pet peso_formatado accessor formats weight correctly.
     */
    public function test_pet_peso_formatado_accessor_formats_weight_correctly()
    {
        $pet = Pet::factory()->create(['peso' => 25.50]);
        
        $this->assertEquals('25.50 kg', $pet->peso_formatado);
    }

    /**
     * Test pet peso_formatado accessor returns null when no weight.
     */
    public function test_pet_peso_formatado_accessor_returns_null_when_no_weight()
    {
        $pet = Pet::factory()->create(['peso' => null]);
        
        $this->assertNull($pet->peso_formatado);
    }

    /**
     * Test pet factory creates valid pet.
     */
    public function test_pet_factory_creates_valid_pet()
    {
        $pet = Pet::factory()->create();
        
        $this->assertNotNull($pet->id);
        $this->assertNotNull($pet->nome);
        $this->assertNotNull($pet->especie);
        $this->assertContains($pet->genero, ['Macho', 'Fêmea']);
        $this->assertNotNull($pet->created_at);
        $this->assertNotNull($pet->updated_at);
    }

    /**
     * Test pet can be soft deleted.
     */
    public function test_pet_can_be_soft_deleted()
    {
        $pet = Pet::factory()->create();
        
        $pet->delete();
        
        $this->assertSoftDeleted('pets', ['id' => $pet->id]);
        $this->assertNotNull($pet->fresh()->deleted_at);
    }

    /**
     * Test soft deleted pets are excluded from queries by default.
     */
    public function test_soft_deleted_pets_are_excluded_from_queries_by_default()
    {
        $activePet = Pet::factory()->create(['nome' => 'Pet Ativo']);
        $deletedPet = Pet::factory()->create(['nome' => 'Pet Deletado']);
        
        $deletedPet->delete();
        
        $pets = Pet::all();
        
        $this->assertCount(1, $pets);
        $this->assertTrue($pets->contains($activePet));
        $this->assertFalse($pets->contains($deletedPet));
    }

    /**
     * Test pet microchip uniqueness.
     */
    public function test_pet_microchip_uniqueness()
    {
        Pet::factory()->create(['numero_microchip' => '123456789012345']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Pet::factory()->create(['numero_microchip' => '123456789012345']);
    }

    /**
     * Test pet can have null microchip.
     */
    public function test_pet_can_have_null_microchip()
    {
        $pet = Pet::factory()->create(['numero_microchip' => null]);
        
        $this->assertNull($pet->numero_microchip);
    }

    /**
     * Test multiple pets can have null microchip.
     */
    public function test_multiple_pets_can_have_null_microchip()
    {
        $pet1 = Pet::factory()->create(['numero_microchip' => null]);
        $pet2 = Pet::factory()->create(['numero_microchip' => null]);
        
        $this->assertNull($pet1->numero_microchip);
        $this->assertNull($pet2->numero_microchip);
        $this->assertNotEquals($pet1->id, $pet2->id);
    }

    /**
     * Test pet can be restored after soft deletion.
     */
    public function test_pet_can_be_restored_after_soft_deletion()
    {
        $pet = Pet::factory()->create();
        
        $pet->delete();
        $this->assertSoftDeleted('pets', ['id' => $pet->id]);
        
        $pet->restore();
        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'deleted_at' => null
        ]);
    }
}