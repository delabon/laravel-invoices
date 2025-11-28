<?php

declare(strict_types=1);

use App\Rules\ValidAddressLine;
use App\Rules\ValidZip;
use App\ValueObjects\Address;
use Illuminate\Validation\ValidationException;

it('does not fail when an address line is valid', function () {
    $rule = new ValidAddressLine();

    $rule->validate('line one', 'Main St 123', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_address_line_data', [
    [
        str_repeat('a', Address::LINE_MAX_LENGTH + 1), // max 20 chars
        'The line one field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid address line', function (string $invalidAddressLine, string $expectedMessage) {
    expect(function () use ($invalidAddressLine) {
        $rule = new ValidAddressLine();

        $rule->validate('lineOne', $invalidAddressLine, function (string $message) {
            $message = str_replace(':attribute', 'line one', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_address_line_data');
