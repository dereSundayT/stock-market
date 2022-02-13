<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

//php artisan test --filter AuthApiFeatureTest
class AuthApiFeatureTest extends TestCase
{
    use RefreshDatabase;
    //login frontend url exist
    public function test_login_form_url()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    //::::::::::::::::::::::::::;
    //login 
    public function test_login_endpoint()
    {
        //prepare
        $user = User::factory()->create(['password' => '123456']);
        $data = [
            'email' => $user->email,
            'password' => '123456'
        ];
        //action
        $response = $this->postJson(route('auth.login'), $data);
        //assert
        $response->assertStatus(200);
    }
    //:::::::::::
    //logout url exist
    public function test_logout_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.logout'));
        $response->assertStatus(200);
    }
}
