<?php

declare(strict_types=1);

use App\Rules\ValidName;
use Illuminate\Validation\ValidationException;

it('does not fail when a name is valid', function () {
    $rule = new ValidName();

    $rule->validate('name', 'John Doe', function (string $message) {
        throw new ValidationException($message);
    });
})->throwsNoExceptions();

dataset('invalid_name_data', [
    [
        str_repeat('a', ValidName::MAX_LENGTH + 1), // larger than max
        'The name field must not be greater than '.ValidName::MAX_LENGTH.' characters.',
    ],
]);

it('fails with invalid name', function (string $invalidName, string $expectedMessage) {
    expect(function () use ($invalidName) {
        $rule = new ValidName();

        $rule->validate('name', $invalidName, function (string $message) {
            $message = str_replace(':attribute', 'name', $message);
            throw new Exception($message);
        });
    })
        ->toThrow(Exception::class, $expectedMessage);
})->with('invalid_name_data');
