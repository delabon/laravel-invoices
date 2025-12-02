<?php

declare(strict_types=1);

use App\Rules\ValidPhone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

it('creates an instance of ValidationRule', function () {
    $rule = new ValidPhone();

    expect($rule)->toBeInstanceOf(ValidationRule::class);
});

it('does not fail when a phone number is valid', function () {
    $rule = new ValidPhone();

    $rule->validate('phone', '123-123-321', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_phone_number_data', [
    [
        str_repeat('a', ValidPhone::MAX_LENGTH + 1), // larger than max
        'The phone field must not be greater than '.ValidPhone::MAX_LENGTH.' characters.',
    ],
]);

it('fails with invalid phone number', function (string $invalidPhone, string $expectedMessage) {
    expect(function () use ($invalidPhone) {
        $rule = new ValidPhone();

        $rule->validate('phone', $invalidPhone, function (string $message) {
            $message = str_replace(':attribute', 'phone', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_phone_number_data');
