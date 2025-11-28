<?php

declare(strict_types=1);

use App\Rules\ValidCity;
use App\ValueObjects\Address;
use Illuminate\Validation\ValidationException;

it('does not fail when a city is valid', function () {
    $rule = new ValidCity();

    $rule->validate('city', 'New York', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_city_data', [
    [
        str_repeat('a', Address::CITY_MAX_LENGTH + 1), // max 50 chars
        'The city field must not be greater than '.Address::CITY_MAX_LENGTH.' characters.',
    ],
]);

it('fails with invalid city', function (string $invalidCity, string $expectedMessage) {
    expect(function () use ($invalidCity) {
        $rule = new ValidCity();

        $rule->validate('city', $invalidCity, function (string $message) {
            $message = str_replace(':attribute', 'city', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_city_data');
