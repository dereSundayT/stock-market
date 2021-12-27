<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Stock;
use Tests\TestCase;

class VirtaulInvestmentTest extends TestCase
{
    //create new client
    public function test_create_new_clients_endpoint()
    {
        $token = getTokenForTest();
        $username = 'jonh_' . time();
        $data = ['username' => $username];
        $response = $this->post('api/v1/virtual-investment/clients', $data, ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(201);
    }

    //get all client
    public function test_all_clients_endpoint()
    {
        $token = getTokenForTest();
        $response = $this->get('api/v1/virtual-investment/clients', ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
    }
    //get all stock for a client
    public function test_all_stock_for_client_endpoint()
    {
        $token = getTokenForTest();
        $id = Client::where('created_by', 2)->first()->id;
        $response = $this->get("api/v1/virtual-investment/clients/$id", ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
    }
    // purchase stock for a client
    public function test_purchase_stock_for_client_endpoint()
    {
        $token = getTokenForTest();
        $client = Client::where('created_by', 2)->first();
        $stock = Stock::where('created_by', 2)->first();
        $data = [
            'client_id' => $client->id,
            'stock_id' => $stock->id,
            'volume' => rand(1, 10),
        ];
        $response = $this->post("api/v1/virtual-investment", $data, ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(201);
    }
}
