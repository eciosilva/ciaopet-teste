<?php

namespace Tests\Unit;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\HasApiTokens;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user has correct fillable attributes.
     */
    public function test_user_has_correct_fillable_attributes()
    {
        $user = new User();
        
        $expectedFillable = ['name', 'email', 'password'];
        
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /**
     * Test user has correct hidden attributes.
     */
    public function test_user_has_correct_hidden_attributes()
    {
        $user = new User();
        
        $expectedHidden = ['password', 'remember_token'];
        
        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /**
     * Test user has correct casts.
     */
    public function test_user_has_correct_casts()
    {
        $user = new User();
        
        $expectedCasts = [
            'id' => 'int',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
        
        $this->assertEquals($expectedCasts, $user->getCasts());
    }

    /**
     * Test user uses HasApiTokens trait.
     */
    public function test_user_uses_has_api_tokens_trait()
    {
        $user = new User();
        
        $this->assertContains(HasApiTokens::class, class_uses($user));
    }

    /**
     * Test user can have many pets.
     */
    public function test_user_can_have_many_pets()
    {
        $user = User::factory()->create();
        $pets = Pet::factory()->count(3)->create(['tutor_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->pets);
        $this->assertCount(3, $user->pets);
        $this->assertTrue($user->pets->contains($pets[0]));
    }

    /**
     * Test user pets relationship returns empty collection when no pets.
     */
    public function test_user_pets_relationship_returns_empty_collection_when_no_pets()
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->pets);
        $this->assertCount(0, $user->pets);
    }

    /**
     * Test user can create API tokens.
     */
    public function test_user_can_create_api_tokens()
    {
        $user = User::factory()->create();
        
        $token = $user->createToken('test-token');
        
        $this->assertInstanceOf(\Laravel\Sanctum\NewAccessToken::class, $token);
        $this->assertStringContainsString('|', $token->plainTextToken);
    }

    /**
     * Test user factory creates valid user.
     */
    public function test_user_factory_creates_valid_user()
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->id);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertTrue(filter_var($user->email, FILTER_VALIDATE_EMAIL) !== false);
    }

    /**
     * Test user can be found by email.
     */
    public function test_user_can_be_found_by_email()
    {
        $user = User::factory()->create(['email' => 'teste@exemplo.com']);
        
        $foundUser = User::where('email', 'teste@exemplo.com')->first();
        
        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }
}