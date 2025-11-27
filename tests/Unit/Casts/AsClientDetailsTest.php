<?php

declare(strict_types=1);

use App\Casts\AsClientDetails;
use App\ValueObjects\Address;
use App\ValueObjects\ClientDetails;
use Database\Factories\ClientFactory;

it('creates a ClientDetails instance from passed value successfully', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-CA',
        city: 'Los Angeles',
        zip: '9001',
        lineOne: 'Main St 123',
        lineTwo: 'N123',
    );
    $clientDetailsArray = [
        'name' => 'Jina Doe',
        'email' => 'jina@doe.test',
        'address' => $address,
        'taxNumber' => 'US-TAX-78623',
        'phone' => '749-4325-464',
    ];
    $clientDetailsJson = json_encode($clientDetailsArray);
    $asClientDetails = new AsClientDetails();

    $clientDetails = $asClientDetails->get(ClientFactory::new()->makeOne(), 'client_details', $clientDetailsJson, []);

    expect($clientDetails)->toBeInstanceOf(ClientDetails::class)
        ->and($clientDetails->name)->toBe($clientDetailsArray['name'])
        ->and($clientDetails->address)->toBeInstanceOf(Address::class)
        ->and($clientDetails->address)->toEqual($clientDetailsArray['address']);
});

test('get method throws InvalidArgumentException when passed value is not of type string', function () {
    $asClientDetails = new AsClientDetails();

    expect(fn () => $asClientDetails->get(ClientFactory::new()->makeOne(), 'client_details', [], []))
        ->toThrow(InvalidArgumentException::class, 'The type of the value argument must be string.');
});

test('get method throws InvalidArgumentException when passed value is not a valid JSON', function () {
    $asClientDetails = new AsClientDetails();

    expect(fn () => $asClientDetails->get(ClientFactory::new()->makeOne(), 'client_details', '^435od09asd asd0as0das0d', []))
        ->toThrow(InvalidArgumentException::class, 'The type of the value argument must be a valid JSON.');
});

it('prepares an array with key and JSON value when setting correctly', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York City',
        zip: '10003',
        lineOne: 'Main St 123',
        lineTwo: 'N123',
    );
    $clientDetailsArray = [
        'name' => 'Mike Doe',
        'address' => $address,
        'email' => 'mike@doe.cc',
        'phone' => null,
        'taxNumber' => 'TAX-543-CAD',
    ];
    $clientDetails = ClientDetails::fromArray($clientDetailsArray);
    $asClientDetails = new AsClientDetails();

    $result = $asClientDetails->set(ClientFactory::new()->makeOne(), 'client_details', $clientDetails, []);

    expect($result)->toBe([
        'client_details' => json_encode($clientDetailsArray),
    ]);
});

test('set method throws InvalidArgumentException when passed value is not an instance of ClientDetails', function () {
    $asClientDetails = new AsClientDetails();

    expect(fn () => $asClientDetails->set(ClientFactory::new()->makeOne(), 'client_details', [], []))
        ->toThrow(InvalidArgumentException::class, 'The given value is not a ClientDetails instance.');
});
