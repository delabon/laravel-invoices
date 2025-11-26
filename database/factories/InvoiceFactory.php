<?php

declare(strict_types=1);

namespace Database\Factories;

use App\ValueObjects\ClientDetails;
use App\ValueObjects\UserDetails;
use Database\Factories\Traits\GeneratesFakeAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
final class InvoiceFactory extends Factory
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
            'client_id' => ClientFactory::new(),
            'client_details' => new ClientDetails(
                name: fake()->name(),
                address: $this->generateFakeAddress('us')
            ),
            'user_details' => new UserDetails(
                name: fake()->name(),
                email: fake()->email(),
                address: $this->generateFakeAddress('us'),
                taxNumber: Str::random(10),
                phone: fake()->phoneNumber()
            ),
            'uid' => Str::random(16),
            'issued_at' => fake()->dateTimeThisMonth(),
            'subtotal' => 1000,
            'tax' => 100,
            'total' => 1100,
        ];
    }
}
