<?php

declare(strict_types=1);

use App\Rules\ValidCountryCode;
use Illuminate\Validation\ValidationException;

it('does not fail when a country code is valid', function () {
    $rule = new ValidCountryCode();

    $rule->validate('countryCode', 'US', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_country_codes', [
    [
        str_repeat('U', ValidCountryCode::CODE_LENGTH - 1), // min 2 chars
        'The country code field must be 2 characters.',
    ],
    [
        str_repeat('U', ValidCountryCode::CODE_LENGTH + 1), // max 2 chars
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
    expect(function () use ($invalidCountryCode) {
        $rule = new ValidCountryCode();

        $rule->validate('countryCode', $invalidCountryCode, function (string $message) {
            $message = str_replace(':attribute', 'country code', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_country_codes');
