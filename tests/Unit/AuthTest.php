<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{

    //login frontend url exist
    public function test_login_form_url()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    //:::::::::::::::::::
    //testing if login endpoint exist
    public function test_login_api_url()
    {
        $response = $this->get('/api/v1/login');
        $response->assertStatus(200);
    }
    //::::::::::::::::::::::::::;
    //login 
    public function test_login_admin()
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'admin@demo.com',
            'password' => '123456'
        ]);
        $response->assertStatus(200);
    }
    //:::::::::::
    //logout url exist
    public function test_logout_api_url()
    {
        // $user = User::where('email', 'admin@demo.com')->first();
        // $token = $user->createToken('admin-login')->plainTextToken;

        $token = getTokenForTest();

        $response = $this->post('/api/v1/logout', [], ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
    }
}
