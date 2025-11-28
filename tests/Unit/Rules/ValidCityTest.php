<?php

declare(strict_types=1);

use App\Rules\ValidCity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

it('creates an instance of ValidationRule', function () {
    $rule = new ValidCity();

    expect($rule)->toBeInstanceOf(ValidationRule::class);
});

it('does not fail when a city is valid', function () {
    $rule = new ValidCity();

    $rule->validate('city', 'New York', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_city_data', [
    [
        str_repeat('a', ValidCity::MAX_LENGTH + 1), // max 50 chars
        'The city field must not be greater than '.ValidCity::MAX_LENGTH.' characters.',
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
