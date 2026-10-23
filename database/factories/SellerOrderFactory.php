<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\SellerOrder;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SellerOrder>
 */
class SellerOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'=>Order::factory(),
            'store_id'=>Store::factory(),
                'shipping_fee'=>5.00,
                'subtotal'=>40.00,
                'total_amount'=>45.00,
        ];
    }
}
