<?php

declare(strict_types=1);

use App\ValueObjects\ClientDetails;
use App\ValueObjects\Address;
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
        address: $address
    );

    expect($clientDetails)->toBeInstanceOf(ClientDetails::class)
        ->and($clientDetails->name)->toBe('John Doe')
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
    ]);

    expect($clientDetails)->toBeInstanceOf(ClientDetails::class)
        ->and($clientDetails->name)->toBe('Mike Doe')
        ->and($clientDetails->address)->toBeInstanceOf(Address::class)
        ->and($clientDetails->address)->toBe($address);
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
        address: $address
    );

    expect($clientDetails->toArray())->toBe([
        'name' => 'Mike Doe',
        'address' => $address,
    ]);
});

dataset('invalid_name_data', [
    [
        '', // empty
        'The name field is required.'
    ],
    [
        str_repeat('a', 256), // max length 255
        'The name field must not be greater than 255 characters.'
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
