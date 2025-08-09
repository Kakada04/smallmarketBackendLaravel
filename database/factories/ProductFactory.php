<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
        "category_id"=>fake()->numberBetween(1,1),
        "product_name"=>fake()->realTextBetween(10,12),
        "quantity"=>fake()->numberBetween(100,200),
        "cost_price"=>fake()->numberBetween(20,200),
        "sell_price"=>fake()->numberBetween(30,300),
        ];
    }
}
