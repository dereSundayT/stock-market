<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Stock;
use Tests\TestCase;

class VirtaulInvestmentTest extends TestCase
{




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
