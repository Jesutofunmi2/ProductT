<?php

namespace Tests\Controller\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_user_can_register_successfull_as_user()
    {

        $email = "joseph@gmail.com";

        $userData = [
            'name' => 'Balogun Joseph',
            'email' => $email,
            'password' => 'Balo5566',
            'password_confirmation' => 'Balo5566',
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => ['token']
        ]);
    $this->assertDatabaseHas((new User)->getTable(), ['email' => $email]);
    }

    public function test_user_cannot_register_with_invalid_credentials()
    {

        $data = [
            'name' => 'Balogun Joseph',
            'email' => 'email',
            'password' => 'Balo5566',
            'password_confirmation' => 'Balo5566',
        ];

        $response = $this->postJson( route('auth.register'), $data);
        $response->assertStatus(422);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        
        $user = User::factory()->create();
        $data = [
            'name' => 'Balogun Joseph',
            'email' => $user->email,
            'password' => 'Balo5566',
            'password_confirmation' => 'Balo5566',
        ];

        $response = $this->postJson( route('auth.register'), $data);
        $response->assertStatus(422);
    }
}
