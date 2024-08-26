<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();   
        
        putenv('APP_ENV=testing');
        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessClient(
            null, 'Personal Access Client', 'http://localhost:8000'
        ); 
        
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]); 
    }

    /** @test */
    public function it_can_register_a_user()
    {  
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

         $response->assertStatus(201)
                ->assertJson([
                    'response_code' => '201',
                    'status' => 'success',
                    'message' => 'User registered successfully.',
                ]);

         $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test */
    public function it_can_login_and_get_access_token()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'response_code',
                     'status',
                     'message',
                     'user_info' => [
                         'id',
                         'name',
                         'email',
                     ],
                     'token',
                 ]);
    }

    /** @test */
    public function it_can_access_protected_route_with_valid_token()
    { 
        $loginResponse = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $accessToken = $loginResponse->json('token');

         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/get-user');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'response_code',
                     'status',
                     'message',
                     'data_user_list' => [
                         'current_page',
                         'data' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'email', 
                             ],
                         ],
                     ],
                 ]);
    }
}
