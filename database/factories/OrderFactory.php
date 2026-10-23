<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
{
    $total = fake()->randomFloat(2, 10, 1000);

    return [
        'user_id' => User::factory(),
        'total_amount' => $total,
        'original_total_amount' => $total,
        'shipping_name' => fake()->name(),
        'shipping_address' => fake()->address(),
        'shipping_phone' => fake()->phoneNumber(),
        'shipping_city' => fake()->city(),
    ];
}
}
