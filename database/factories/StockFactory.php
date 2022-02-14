<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'company_name' => $this->faker->sentence(),
            'unit_price' => $this->faker->numerify('#'),
            'status' => 1,
            'created_by' => function () {
                return User::factory()->create();
            }
        ];
    }
}
