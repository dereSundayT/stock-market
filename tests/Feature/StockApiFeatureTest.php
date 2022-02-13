<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

//php artisan test --filter StockApiFeatureTest
class StockApiFeatureTest extends TestCase
{
    use RefreshDatabase;
    #
    public function test_get_all_stock_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $stock = Stock::factory()->create();

        $response = $this->getJson(route('stocks.index'));
        $response->assertStatus(200);
    }
    //store
    public function test_create_stock_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $stock = Stock::factory()->raw();

        $response = $this->postJson('/api/v1/stocks', $stock)
            ->assertCreated();
    }
    //update
    public function test_update_stock_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $stock = Stock::factory()->create();
        $data = [
            'unit_price' => 4,
        ];
        //
        $response = $this->putJson(route('stocks.update', $stock->id), $data)
            ->assertOk();
    }
    //destroy
    public function test_delete_stock_endpoint()
    {
        //
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $stock = Stock::factory()->create();
        //
        $response = $this->deleteJson(route("stocks.destroy", $stock->id))
            ->assertOk();
    }
    //testing frontend jurl
    public function test_stock_frontend_url()
    {
        $response = $this->get('/stocks')
            ->assertStatus(200);
    }
}
