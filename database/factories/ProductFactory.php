<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'category_id'=>Category::factory(),
            'store_id'=>Store::factory(),
            'name'=>fake()->word(),
            'price'=>fake()->randomFloat(2,10,1000),
            'stock_quantity'=>fake()->numberBetween(1,100)
        ];
    }
}
