<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
final class InvoiceFactory extends Factory
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
            'client_id' => Client::factory(),
            'uid' => Str::random(),
            'title' => $this->faker->sentence(),
            'currency' => 'USD',
            'tax_amount' => $this->faker->numberBetween(100, 1000),
            'total_amount' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
