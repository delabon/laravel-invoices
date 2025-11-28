<?php

declare(strict_types=1);

use App\Rules\ValidTaxNumber;
use Illuminate\Validation\ValidationException;

it('does not fail when a tax number is valid', function () {
    $rule = new ValidTaxNumber();

    $rule->validate('taxNumber', 'TAX-123-321', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_tax_number_data', [
    [
        str_repeat('a', ValidTaxNumber::MAX_LENGTH + 1), // larger than max
        'The tax number field must not be greater than '.ValidTaxNumber::MAX_LENGTH.' characters.',
    ],
]);

it('fails with invalid name', function (string $invalidTaxNumber, string $expectedMessage) {
    expect(function () use ($invalidTaxNumber) {
        $rule = new ValidTaxNumber();

        $rule->validate('taxNumber', $invalidTaxNumber, function (string $message) {
            $message = str_replace(':attribute', 'tax number', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_tax_number_data');
