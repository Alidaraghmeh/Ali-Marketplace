<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use PHPUnit\Util\Test;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seller_order_id'=>SellerOrder::factory(),
            'product_id'=>Product::factory(),
            'quantity'=>2,
            'product_name'=>'Test Product',
            'unit_price'=>20.00

        ];
    }
}
