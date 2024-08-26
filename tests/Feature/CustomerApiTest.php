<?php  
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use Laravel\Passport\Passport;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

         $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Passport::actingAs($this->user, ['*']);   
    }

    /** @test */
    public function it_can_list_all_customers()
    {
        $customer = Customer::factory()->create();  

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $customer->id]);
    }

    /** @test */
    public function it_can_show_a_single_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $customer->id]);
    }
 
    /** @test */
    public function it_can_create_a_new_customer()
    {
        $dob = '1994-05-21';
        $creation_date = now()->toDateTimeString();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 30,
            'dob' => $dob,
            'email' => 'john.doe@example.com',
            'creation_date' => $creation_date
        ];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'age' => 30,
                    'dob' => "{$dob}T00:00:00.000000Z",  
                    'email' => 'john.doe@example.com'
                ]);
    }

    /** @test */
    public function it_can_update_an_existing_customer()
    {
        $customer = Customer::factory()->create();
        $data = ['first_name' => 'Jane'];

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'Jane']);
    }

    /** @test */
    public function it_can_delete_a_customer()
    { 
        $customer = Customer::factory()->create(); 
        $response = $this->deleteJson("/api/customers/{$customer->id}"); 
        $response->assertStatus(200)
                 ->assertJson([
                     'response_code' => '200',
                     'status' => 'success',
                     'message' => 'Customer deleted successfully.',
                 ]);
 
    } 
}
