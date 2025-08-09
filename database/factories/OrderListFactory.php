<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderList>
 */
class OrderListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
             "user_id"=>fake()->numberBetween(8,9),
            "total_price"=>fake()->numberBetween(200,900),
            "order_date"=>fake()->date(),
        ];
    }
}
