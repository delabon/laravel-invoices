<?php

declare(strict_types=1);

use App\Models\Client;
use App\Rules\ValidTaxNumber;
use App\ValueObjects\Address;
use App\ValueObjects\ClientDetails;
use Illuminate\Validation\ValidationException;

it('creates an instance of ClientDetails successfully', function () {
    $address = new Address(
        countryCode: 'TN',
        regionCode: 'TN-23',
        city: 'Jarzouna',
        zip: 'TEST-7021',
        lineOne: 'Place 123',
        lineTwo: 'N45',
    );
    $clientDetails = new ClientDetails(
        name: 'John Doe',
        address: $address,
        email: 'john@doe.cc',
        phone: '123-123-432',
        taxNumber: 'DC-2148-4214',
    );

    expect($clientDetails)->toBeInstanceOf(ClientDetails::class)
        ->and($clientDetails->name)->toBe('John Doe')
        ->and($clientDetails->email)->toBe('john@doe.cc')
        ->and($clientDetails->phone)->toBe('123-123-432')
        ->and($clientDetails->taxNumber)->toBe('DC-2148-4214')
        ->and($clientDetails->address)->toBeInstanceOf(Address::class)
        ->and($clientDetails->address)->toBe($address);
});

it('creates an instance of ClientDetails from an array of data', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York City',
        zip: '10003',
        lineOne: 'Main St 123',
        lineTwo: 'N123',
    );
    $clientDetails = ClientDetails::fromArray([
        'name' => 'Mike Doe',
        'address' => $address,
        'email' => 'mike@doe.cc',
        'phone' => '667-123-432',
        'taxNumber' => 'TC-1234-4214',
    ]);

    expect($clientDetails)->toBeInstanceOf(ClientDetails::class)
        ->and($clientDetails->name)->toBe('Mike Doe')
        ->and($clientDetails->email)->toBe('mike@doe.cc')
        ->and($clientDetails->phone)->toBe('667-123-432')
        ->and($clientDetails->taxNumber)->toBe('TC-1234-4214')
        ->and($clientDetails->address)->toBeInstanceOf(Address::class)
        ->and($clientDetails->address)->toBe($address);
});

it('throws an InvalidArgumentException when create an instance of ClientDetails from an array with invalid address', function () {
    expect(static fn () => ClientDetails::fromArray([
        'name' => 'Mike Doe',
        'address' => '',
        'email' => 'mike@doe.cc',
        'phone' => '667-123-432',
        'taxNumber' => 'TC-1234-4214',
    ]))->toThrow(InvalidArgumentException::class, 'The address parameter must be an instance of Address value object.');
});

test('to array', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );
    $clientDetails = new ClientDetails(
        name: 'Mike Doe',
        address: $address,
        email: 'john@doe.cc',
        phone: '123-123-432',
        taxNumber: 'DC-2148-4214',
    );

    expect($clientDetails->toArray())->toBe([
        'name' => 'Mike Doe',
        'address' => $address,
        'email' => 'john@doe.cc',
        'phone' => '123-123-432',
        'taxNumber' => 'DC-2148-4214',
    ]);
});

dataset('invalid_name_data', [
    [
        '', // empty
        'The name field is required.',
    ],
    [
        str_repeat('a', Client::NAME_MAX_LENGTH + 1), // max length 255
        'The name field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid name data', function (string $invalidName, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new ClientDetails(
        name: $invalidName,
        address: $address
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_name_data');

dataset('invalid_email_data', [
    [
        '&$43 9034 3*I&%$ 320 #$0032 --0 @#$#', // invalid
        'The email field must be a valid email address.',
    ],
]);

it('fails with invalid email data', function (string $invalidEmail, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new ClientDetails(
        name: 'John Doe',
        address: $address,
        email: $invalidEmail
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_email_data');

dataset('invalid_tax_number_data', [
    [
        str_repeat('a', ValidTaxNumber::MAX_LENGTH + 1), // more than max
        'The tax number field must not be greater than 50 characters.',
    ],
]);

it('fails with invalid tax number data', function (string $invalidTaxNumber, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new ClientDetails(
        name: 'John Doe',
        address: $address,
        taxNumber: $invalidTaxNumber
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_tax_number_data');

dataset('invalid_phone_data', [
    [
        str_repeat('a', Client::PHONE_MAX_LENGTH + 1), // more than max
        'The phone field must not be greater than 20 characters.',
    ],
]);

it('fails with invalid phone data', function (string $invalidPhone, string $expectedMessage) {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York',
        zip: '10001',
        lineOne: 'Main St 123',
        lineTwo: null,
    );

    expect(fn () => new ClientDetails(
        name: 'John Doe',
        address: $address,
        phone: $invalidPhone
    ))->toThrow(ValidationException::class, $expectedMessage);
})->with('invalid_phone_data');
