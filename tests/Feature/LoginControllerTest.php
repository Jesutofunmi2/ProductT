<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_login_successful()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson( route('auth.login'), $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token']
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
            'password' => 'fakepassword'
        ];

        $response = $this->postJson( route('auth.login'), $data);
        $response->assertStatus(422);
    }
}
