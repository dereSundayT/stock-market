<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class VirtualInvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => function () {
                return Client::factory()->create();
            },
            'stock_id' => function () {
                return Stock::factory()->create();
            },
            'volume' => $this->faker->numerify('#'),
            'purchase_price' => $this->faker->numerify('###'),
            'created_by' => function () {
                return User::factory()->create();
            }

        ];
    }
}
