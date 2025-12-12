<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
final class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'symbol' => $this->faker->currencyCode(),
            'side' => $this->faker->randomElement(OrderSide::cases()),
            'price' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
            'amount' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
            'status' => $this->faker->randomElement(OrderStatus::cases()),
        ];
    }
}
