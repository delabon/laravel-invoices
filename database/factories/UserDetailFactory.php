<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Region;
use App\ValueObjects\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regionIds = Region::query()
            ->where('country_id', 'tn')
            ->pluck('code')
            ->map(static fn (string $code) => strtoupper($code))
            ->all();

        return [
            'user_id' => UserFactory::new(),
            'address' => Address::fromArray([
                'countryCode' => 'TN',
                'regionCode' => fake()->randomElement($regionIds),
                'city' => fake()->city(),
                'zip' => fake()->postcode(),
                'lineOne' => fake()->streetAddress(),
                'lineTwo' => fake()->streetAddress(),
            ]),
            'phone' => fake()->phoneNumber(),
            'tax_number' => Str::random('16'),
        ];
    }
}
