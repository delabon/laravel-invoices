<?php

declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\Traits\GeneratesFakeAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
final class ClientFactory extends Factory
{
    use GeneratesFakeAddress;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'name' => fake()->name(),
            'address' => $this->generateFakeAddress('us'),
        ];
    }
}
