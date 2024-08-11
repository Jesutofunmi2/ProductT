<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfull_as_user()
    {
        $this->withoutMiddleware();
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
        $this->withoutMiddleware();

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
        $this->withoutMiddleware();

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
