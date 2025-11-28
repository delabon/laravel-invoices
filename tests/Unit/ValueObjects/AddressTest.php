<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Region;
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
        '', // Missing code
        'The country code field is required.',
    ],
    [
        str_repeat('U', Country::CODE_LENGTH - 1), // min 2 chars
        'The country code field must be 2 characters.',
    ],
    [
        str_repeat('U', Country::CODE_LENGTH + 1), // max 2 chars
        'The country code field must be 2 characters.',
    ],
    [
        '1$', // non-letters
        'The country code field format is invalid.',
    ],
    [
        'zz', // only uppercase chars
        'The country code field format is invalid.',
    ],
    [
        'ZZ', // code that does not exist
        'The selected country code is invalid.',
    ],
]);

it('fails with invalid country codes', function (string $invalidCountryCode, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => $invalidCountryCode,
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '9002',
        'lineOne' => 'Place 123',
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_country_codes');

dataset('invalid_region_codes', [
    [
        '', // Missing code
        'The region code field is required.',
    ],
    [
        str_repeat('U', Region::CODE_MIN_LENGTH - 1), // min 3 chars
        'The region code field must be at least 3 characters.',
    ],
    [
        'US-CALIFORNIA', // max 6 chars
        'The region code field must not be greater than 6 characters.',
    ],
    [
        '1$-1$', // non-letters
        'The region code field format is invalid.',
    ],
    [
        'us-zz', // code that does not exist
        'The region code field format is invalid.',
    ],
    [
        'US-ZZ', // code that does not exist
        'The selected region code is invalid.',
    ],
]);

it('fails with invalid region codes', function (string $invalidRegionCode, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => $invalidRegionCode,
        'city' => 'Los Angeles',
        'zip' => '9002',
        'lineOne' => 'Place 123',
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_region_codes');

dataset('invalid_city_names', [
    [
        '', // Empty
        'The city field is required.',
    ],
    [
        str_repeat('a', Address::CITY_MAX_LENGTH + 1), // max 50 chars
        'The city field must not be greater than 50 characters.',
    ],
]);

it('fails with invalid city names', function (string $invalidCity, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => $invalidCity,
        'zip' => '9002',
        'lineOne' => 'Place 123',
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_city_names');

dataset('invalid_zip_codes', [
    [
        '', // Empty
        'The zip field is required.',
    ],
    [
        str_repeat('a', Address::ZIP_MAX_LENGTH + 1), // max 20 chars
        'The zip field must not be greater than 20 characters.',
    ],
    [
        '!@# 3c**&^%$_+', // must only contain a-z 0-9 - case-insensitive
        'The zip field format is invalid.',
    ],
    [
        '-2303', // starts with -
        'The zip field format is invalid.',
    ],
    [
        '7042-', // ends with -
        'The zip field format is invalid.',
    ],
]);

it('fails with invalid zip codes', function (string $invalidZip, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => $invalidZip,
        'lineOne' => 'Place 123',
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_zip_codes');

dataset('invalid_line_one_data', [
    [
        '', // Empty
        'The line one field is required.',
    ],
    [
        str_repeat('a', Address::LINE_MAX_LENGTH + 1), // max 20 chars
        'The line one field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid line one address', function (string $invalidLineOne, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '9003',
        'lineOne' => $invalidLineOne,
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_line_one_data');

dataset('invalid_line_two_data', [
    [
        str_repeat('a', Address::LINE_MAX_LENGTH + 1), // max 20 chars
        'The line two field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid line two address', function (string $invalidLineTwo, string $expectedMessage) {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '7000',
        'lineOne' => 'Place 123',
        'lineTwo' => $invalidLineTwo,
    ]))
        ->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_line_two_data');
