<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trade>
 */
final class TradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'buy_order_id' => Order::factory() ?? null,
            'sell_order_id' => Order::factory() ?? null,
            'price' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
            'amount' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
            'usd_volume' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
            'fee_usd' => number_format($this->faker->randomFloat(2, 10), 8, '.', ''),
        ];
    }
}
