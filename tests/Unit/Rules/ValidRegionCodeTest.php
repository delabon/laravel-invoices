<?php

declare(strict_types=1);

use App\Rules\ValidRegionCode;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

it('does not fail when a region code is valid', function () {
    $rule = new ValidRegionCode();

    $rule->validate('regionCode', 'US-CA', function (string $message) {
        throw new ValidationException($message);
    });

    expect($rule)->toBeInstanceOf(ValidationRule::class);
})->throwsNoExceptions();

dataset('invalid_region_codes', [
    [
        str_repeat('U', ValidRegionCode::CODE_MIN_LENGTH - 1), // min 3 chars
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
    expect(function () use ($invalidRegionCode) {
        $rule = new ValidRegionCode();

        $rule->validate('regionCode', $invalidRegionCode, function (string $message) {
            $message = str_replace(':attribute', 'region code', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_region_codes');
