<?php

namespace Tests\Unit;

use App\Models\Stock;
use Tests\TestCase;

class StockTest extends TestCase
{
    public function test_get_all_stock()
    {
        $stocks = Stock::all();
        if ($stocks) {
            $this->assertTrue(true);
        }
    }
    //store
    public function test_create_stock()
    {
        $token = getTokenForTest();
        $company_name = "test_" . time();
        $response = $this->post('/api/v1/stocks', [
            'company_name' => $company_name,
            'unit_price' => '2'
        ], ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);

        $response->assertStatus(201);
    }
    //update
    public function test_update_stock()
    {
        $stock = Stock::where('created_by', 1)->first();
        $id = $stock->id;
        $token = getTokenForTest();
        $company_name = "test_" . time();
        //
        $response = $this->put("/api/v1/stocks/$id", [
            'company_name' => $company_name,
            'unit_price' => '3'
        ], ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);

        $response->assertStatus(200);
    }
    //destroy
    public function test_delete_stock()
    {
        //
        $stock = Stock::where('created_by', 1)->first();
        $token = getTokenForTest();
        $response = $this->delete("/api/v1/stocks/1", [], ['Accept' => 'application/json', 'Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
    }


    //testing frontend jurl
    public function test_stock_frontend_url()
    {
        $response = $this->get('/stocks');
        $response->assertStatus(200);
    }
}
