<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
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
            'username' => $this->faker->userName(),
            'virtual_wallet' => 1000,
            'created_by' => function () {
                return User::factory()->create();
            }
        ];
    }
}
