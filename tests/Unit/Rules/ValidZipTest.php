<?php

declare(strict_types=1);

use App\Rules\ValidZip;
use App\ValueObjects\Address;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

it('does not fail when a zip is valid', function () {
    $rule = new ValidZip();

    $rule->validate('zip', '10001', function (string $message) {
        throw new ValidationException($message);
    });

    expect($rule)->toBeInstanceOf(ValidationRule::class);
})->throwsNoExceptions();

dataset('invalid_zip_data', [
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

it('fails with invalid zip', function (string $invalidZip, string $expectedMessage) {
    expect(function () use ($invalidZip) {
        $rule = new ValidZip();

        $rule->validate('zip', $invalidZip, function (string $message) {
            $message = str_replace(':attribute', 'zip', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_zip_data');
