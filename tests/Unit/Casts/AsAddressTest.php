<?php

declare(strict_types=1);

use App\Casts\AsAddress;
use App\ValueObjects\Address;
use Database\Factories\ClientFactory;

it('create an address instance from passed value successfully', function () {
    $addressArray = [
        'countryCode' => 'US',
        'regionCode' => 'CA',
        'city' => 'Los Angeles',
        'zip' => '9003',
        'lineOne' => 'Place 123',
        'lineTwo' => 'N45',
    ];
    $addressJson = json_encode($addressArray);
    $asAddress = new AsAddress();

    $address = $asAddress->get(ClientFactory::new()->makeOne(), 'address', $addressJson, []);

    expect($address)->toBeInstanceOf(Address::class)
        ->and($address->countryCode)->toBe($addressArray['countryCode'])
        ->and($address->regionCode)->toBe($addressArray['regionCode'])
        ->and($address->city)->toBe($addressArray['city'])
        ->and($address->zip)->toBe($addressArray['zip'])
        ->and($address->lineOne)->toBe($addressArray['lineOne'])
        ->and($address->lineTwo)->toBe($addressArray['lineTwo']);
});

test('get method throws InvalidArgumentException when passed value is not of type string', function () {
    $asAddress = new AsAddress();

    expect(fn () => $asAddress->get(ClientFactory::new()->makeOne(), 'address', [], []))
        ->toThrow(InvalidArgumentException::class, 'The type of the value argument must be string.');
});

test('get method throws InvalidArgumentException when passed value is not a valid JSON', function () {
    $asAddress = new AsAddress();

    expect(fn () => $asAddress->get(ClientFactory::new()->makeOne(), 'address', 'asdod09asd asd0as0das0d', []))
        ->toThrow(InvalidArgumentException::class, 'The type of the value argument must be a valid JSON.');
});

it('prepares an array with key and JSON value when setting correctly', function () {
    $addressArray = [
        'countryCode' => 'TN',
        'regionCode' => 'BZ',
        'city' => 'Jarzouna',
        'zip' => '7020',
        'lineOne' => 'Place 456',
        'lineTwo' => 'No place like home',
    ];
    $address = Address::fromArray($addressArray);
    $asAddress = new AsAddress();

    $result = $asAddress->set(ClientFactory::new()->makeOne(), 'address', $address, []);

    expect($result)->toBe([
        'address' => json_encode($addressArray),
    ]);
});

test('set method throws InvalidArgumentException when passed value is not an instance of Address', function () {
    $asAddress = new AsAddress();

    expect(fn () => $asAddress->set(ClientFactory::new()->makeOne(), 'address', [], []))
        ->toThrow(InvalidArgumentException::class, 'The given value is not an Address instance.');
});
