<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
final class AssetFactory extends Factory
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
            'amount' => number_format($this->faker->randomFloat(8, 0, 1000000), 8, '.', ''),
            'locked_amount' => number_format($this->faker->randomFloat(8, 0, 1000000), 8, '.', ''),
        ];
    }
}
