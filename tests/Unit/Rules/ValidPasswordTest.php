<?php

declare(strict_types=1);

use App\Rules\ValidPassword;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

it('creates an instance of ValidationRule', function () {
    $rule = new ValidPassword();

    expect($rule)->toBeInstanceOf(ValidationRule::class);
});

it('does not fail when a password is valid', function () {
    $rule = new ValidPassword();

    $rule->validate('password', '12345678', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_password_data', [
    [
        str_repeat('a', ValidPassword::MIN_LENGTH - 1), // smaller than min
        'The password field must be at least '.ValidPassword::MIN_LENGTH.' characters.',
    ],
    [
        str_repeat('a', ValidPassword::MAX_LENGTH + 1), // larger than min
        'The password field must not be greater than '.ValidPassword::MAX_LENGTH.' characters.',
    ],
]);

it('fails with invalid password', function (string $invalidPassword, string $expectedMessage) {
    expect(function () use ($invalidPassword) {
        $rule = new ValidPassword();

        $rule->validate('password', $invalidPassword, function (string $message) {
            $message = str_replace(':attribute', 'password', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_password_data');
