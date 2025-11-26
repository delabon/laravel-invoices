<?php

declare(strict_types=1);

use App\ValueObjects\Address;
use Illuminate\Validation\ValidationException;

it('create an instance of Address correctly', function () {
    $address = new Address(
        countryCode: 'TN',
        regionCode: 'TN-23',
        city: 'Jarzouna',
        zip: 'TEST-7021',
        lineOne: 'Place 123',
        lineTwo: 'N45',
    );

    expect($address)->toBeInstanceOf(Address::class)
        ->and($address->countryCode)->toBe('TN')
        ->and($address->regionCode)->toBe('TN-23')
        ->and($address->city)->toBe('Jarzouna')
        ->and($address->zip)->toBe('TEST-7021')
        ->and($address->lineOne)->toBe('Place 123')
        ->and($address->lineTwo)->toBe('N45');
});

dataset('invalid_country_codes', [
    [
        [
            'countryCode' => '', // Missing code
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The country code field is required.'
    ],
    [
        [
            'countryCode' => 'U', // min 2 chars
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The country code field must be 2 characters.'
    ],
    [
        [
            'countryCode' => 'USA', // max 2 chars
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The country code field must be 2 characters.'
    ],
    [
        [
            'countryCode' => '1$', // non-letters
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The country code field format is invalid.'
    ],
    [
        [
            'countryCode' => 'zz', // code that does not exist
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The selected country code is invalid.'
    ],
]);

it('fails with invalid country codes', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_country_codes');

dataset('invalid_region_codes', [
    [
        [
            'countryCode' => 'US',
            'regionCode' => '', // Missing code
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The region code field is required.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'U', // min 3 chars
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The region code field must be at least 3 characters.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CALIFORNIA', // max 6 chars
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The region code field must not be greater than 6 characters.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => '1$-1$', // non-letters
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The region code field format is invalid.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-ZZ', // code that does not exist
            'city' => 'Los Angeles',
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The selected region code is invalid.'
    ],
]);

it('fails with invalid region codes', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_region_codes');

dataset('invalid_city_names', [
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => '', // Empty
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The city field is required.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => str_repeat('a', 60), // max 50 chars
            'zip' => '9002',
            'lineOne' => 'Place 123',
        ],
        'The city field must not be greater than 50 characters.'
    ],
]);

it('fails with invalid city names', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_city_names');

dataset('invalid_zip_codes', [
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '', // Empty
            'lineOne' => 'Place 123',
        ],
        'The zip field is required.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => str_repeat('a', 21), // max 20 chars
            'lineOne' => 'Place 123',
        ],
        'The zip field must not be greater than 20 characters.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '!@# 3c**&^%$_+', // must only contain a-z 0-9 - case-insensitive
            'lineOne' => 'Place 123',
        ],
        'The zip field format is invalid.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '-2303', // starts with -
            'lineOne' => 'Place 123',
        ],
        'The zip field format is invalid.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '7042-', // ends with -
            'lineOne' => 'Place 123',
        ],
        'The zip field format is invalid.'
    ],
]);

it('fails with invalid zip codes', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_zip_codes');

dataset('invalid_line_one_data', [
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '9003',
            'lineOne' => '', // Empty
        ],
        'The line one field is required.'
    ],
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '7000',
            'lineOne' => str_repeat('a', 256), // max 20 chars
        ],
        'The line one field must not be greater than 255 characters.'
    ],
]);

it('fails with invalid line one address', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_line_one_data');

dataset('invalid_line_two_data', [
    [
        [
            'countryCode' => 'US',
            'regionCode' => 'US-CA',
            'city' => 'Los Angeles',
            'zip' => '7000',
            'lineOne' => 'Place 123',
            'lineTwo' => str_repeat('a', 256), // max 20 chars
        ],
        'The line two field must not be greater than 255 characters.'
    ],
]);

it('fails with invalid line two address', function (array $data, string $expectedMessage) {
    expect(fn () => Address::fromArray($data))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_line_two_data');
