<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
    }

    public function test_login_returns_token()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Test Device'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'user',
                         'token',
                         'token_type'
                     ]
                 ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'The provided credentials are incorrect.'
                 ]);
    }

    public function test_protected_routes_require_authentication()
    {
        $response = $this->getJson('/api/access/students/123/info');
        
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_protected_routes()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/access/students/123/info');

        // Should pass authentication and reach the controller
        // Will return 500 if ACCESS API credentials are not configured
        $this->assertTrue(in_array($response->status(), [200, 500]));
        
        // Ensure it's not a 401 (authentication failed)
        $response->assertStatus(200);
    }

    public function test_user_can_logout()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Successfully logged out'
                 ]);
    }

    public function test_user_can_get_profile()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'email' => 'test@example.com'
                     ]
                 ]);
    }
}