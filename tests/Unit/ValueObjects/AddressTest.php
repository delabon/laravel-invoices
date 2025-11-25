<?php

declare(strict_types=1);

use App\ValueObjects\Address;

it('create an instance of Address correctly', function () {
    $address = new Address(
        countryCode: 'TN',
        regionCode: 'BZ',
        city: 'Jarzouna',
        zip: '7021',
        lineOne: 'Place 123',
        lineTwo: 'N45',
    );

    expect($address)->toBeInstanceOf(Address::class)
        ->and($address->countryCode)->toBe('TN')
        ->and($address->regionCode)->toBe('BZ')
        ->and($address->city)->toBe('Jarzouna')
        ->and($address->zip)->toBe('7021')
        ->and($address->lineOne)->toBe('Place 123')
        ->and($address->lineTwo)->toBe('N45');
});

it('throws InvalidArgumentException when countryCode is empty', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => '',
    ]))->toThrow(InvalidArgumentException::class, 'The country code property is empty.');
});

it('throws InvalidArgumentException when regionCode is empty', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => '',
    ]))->toThrow(InvalidArgumentException::class, 'The region code property is empty.');
});

it('throws InvalidArgumentException when city is empty', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'CA',
        'city' => '',
    ]))->toThrow(InvalidArgumentException::class, 'The city property is empty.');
});

it('throws InvalidArgumentException when zip is empty', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'CA',
        'city' => 'Los Angeles',
        'zip' => '',
    ]))->toThrow(InvalidArgumentException::class, 'The zip property is empty.');
});

it('throws InvalidArgumentException when lineOne is empty', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'CA',
        'city' => 'Los Angeles',
        'zip' => '9001',
        'lineOne' => '',
    ]))->toThrow(InvalidArgumentException::class, 'The line one property is empty.');
});

it('throws InvalidArgumentException when zip is invalid', function () {
    expect(fn () => Address::fromArray([
        'countryCode' => 'US',
        'regionCode' => 'CA',
        'city' => 'Los Angeles',
        'zip' => '!@#1 102312 321#!@#',
        'lineOne' => 'Place 123',
    ]))->toThrow(InvalidArgumentException::class, 'The zip property is invalid.');
});
