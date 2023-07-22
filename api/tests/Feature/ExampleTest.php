<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;


    public function test_database_works()
    {
        User::factory(20)->create();

        $this->assertEquals(20, User::all()->count());
    }

    public function test_users_endpoint_returns_a_successful_response()
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    public function test_users_endpoint_returns_a_list_of_users()
    {
        User::factory(20)->create();

        $response = $this->get('/users');

        $response->assertJsonCount(20, 'users');
    }

    public function test_weather_endpoint_returns_a_successful_response()
    {
        $response = $this->get('/weather?lat=40.7128&lon=74.0060');

        $response->assertStatus(200);
    }

    // test getting the weather for a user
    public function test_user_weather_endpoint_returns_a_successful_response()
    {
        $user = User::factory()->create();

        $response = $this->get("/weather/{$user->id}");

        $response->assertStatus(200);
    }
}
