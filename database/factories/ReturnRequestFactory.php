<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\User;

use App\Models\ReturnRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReturnRequest>
 */
class ReturnRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_item_id'=>OrderItem::factory(),
            'user_id'=>User::factory(),
            'quantity'=>fake()->randomNumber(),
            'reason'=>fake()->text()
        ];
    }
}
