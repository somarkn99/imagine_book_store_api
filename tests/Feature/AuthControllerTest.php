<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Seed the database before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testLogin()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Make a POST request to the login endpoint with valid credentials
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assert that the response has a success status
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'user', 'authorization']);
    }

    /**
     * Test the register endpoint.
     */
    public function testRegister()
    {
        // Make a POST request to the register endpoint with valid data
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'tesst@example.com',
            'password' => 'newpassword',
        ]);

        // Assert that the response has a success status
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'user', 'authorization']);
    }

    /**
     * Test the logout endpoint.
     */
    public function testLogout()
    {
        // Create a user for testing
        $user = User::factory()->create();

        // Generate a token for the user
        $token = auth()->login($user);

        // Make a POST request to the logout endpoint with the bearer token
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->post('/api/logout');

        // Assert that the response has a success status
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'message' => 'Successfully logged out']);
    }

    /**
     * Test the refresh endpoint.
     */
    public function testRefresh()
    {
        // Create a user for testing
        $user = User::factory()->create();

        // Generate a token for the user
        $token = auth()->login($user);

        // Make a POST request to the refresh endpoint with the bearer token
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->post('/api/refresh');

        // Assert that the response has a success status
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'user', 'authorization']);
    }
}
