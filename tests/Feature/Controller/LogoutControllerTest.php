<?php

namespace Tests\Controller\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_logout_successful()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson( route('auth.logout'));
        $response->assertStatus(200);
    }
}
