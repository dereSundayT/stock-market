<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\Client;
use App\Models\VirtualInvestment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


//php artisan test --filter VirtaulInvestmentApiFeatureTest
class VirtaulInvestmentApiFeatureTest extends TestCase
{
    use RefreshDatabase;
    //create new client
    public function test_create_new_clients_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $client = Client::factory()->raw();


        $response = $this->postJson(route('virtual.clients.store'), $client)
            ->assertCreated();
    }

    //get all client
    public function test_all_clients_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $client = Client::factory()->create();

        $response = $this->getJson(route('virtual.clients.index'))
            ->assertOk();
    }

    //get all stock for a client
    public function test_all_stock_for_client_endpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $stock =  Stock::factory()->create();
        $client = Client::factory()->create();
        //
        $purchased  = VirtualInvestment::factory()->create([
            'client_id' => $client->id,
            'stock_id' => $stock->id,
            'created_by' => $user->id
        ]);


        $response = $this->getJson(route("virtual.clients.stock", $client->id))
            ->assertOk();


        //TODO assert that the data fetched is for the right user
    }

    //DB structure allows entering negative values for volume and stock due to the usage of decimal data type.
}
