<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Region;
use App\ValueObjects\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
final class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regionIds = Region::query()
            ->where('country_id', 'us')
            ->pluck('code')
            ->map(static fn (string $code) => strtoupper($code))
            ->all();

        return [
            'user_id' => UserFactory::new(),
            'name' => fake()->name(),
            'address' => Address::fromArray([
                'countryCode' => 'US',
                'regionCode' => fake()->randomElement($regionIds),
                'city' => fake()->city(),
                'zip' => fake()->postcode(),
                'lineOne' => fake()->streetAddress(),
                'lineTwo' => fake()->streetAddress(),
            ]),
        ];
    }
}
