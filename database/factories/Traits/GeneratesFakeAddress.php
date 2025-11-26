<?php

declare(strict_types=1);

namespace Database\Factories\Traits;

use App\Models\Country;
use App\Models\Region;
use App\ValueObjects\Address;

trait GeneratesFakeAddress
{
    public function generateFakeAddress(string $countryCode = 'us'): Address
    {
        Country::query()->findOrFail($countryCode);

        $regionIds = Region::query()
            ->where('country_id', $countryCode)
            ->pluck('code')
            ->map(static fn (string $code) => strtoupper($code))
            ->all();

        return Address::fromArray([
            'countryCode' => strtoupper($countryCode),
            'regionCode' => fake()->randomElement($regionIds),
            'city' => fake()->city(),
            'zip' => fake()->postcode(),
            'lineOne' => fake()->streetAddress(),
            'lineTwo' => fake()->streetAddress(),
        ]);
    }
}
