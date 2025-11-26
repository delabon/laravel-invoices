<?php

declare(strict_types=1);

use App\Casts\AsUserDetails;
use App\ValueObjects\Address;
use App\ValueObjects\UserDetails;
use Database\Factories\UserDetailFactory;

it('creates a UserDetails instance from passed value successfully', function () {
    $address = new Address(
        countryCode: 'US',
        regionCode: 'US-NY',
        city: 'New York City',
        zip: '10003',
        lineOne: 'Main St 123',
        lineTwo: 'N123',
    );
    $userDetailsArray = [
        'name' => 'Mike Doe',
        'email' => 'mike@doe.test',
        'address' => $address,
        'taxNumber' => 'US-TAX-9912',
        'phone' => '0023-234-213',
    ];
    $userDetailsJson = json_encode($userDetailsArray);
    $asUserDetails = new AsUserDetails();

    $userDetails = $asUserDetails->get(UserDetailFactory::new()->makeOne(), 'user_details', $userDetailsJson, []);

    expect($userDetails)->toBeInstanceOf(UserDetails::class)
        ->and($userDetails->name)->toBe($userDetailsArray['name'])
        ->and($userDetails->email)->toBe($userDetailsArray['email'])
        ->and($userDetails->address)->toBeInstanceOf(Address::class)
        ->and($userDetails->address)->toEqual($userDetailsArray['address'])
        ->and($userDetails->taxNumber)->toBe($userDetailsArray['taxNumber'])
        ->and($userDetails->phone)->toBe($userDetailsArray['phone']);
});

test('get method throws InvalidArgumentException when passed value is not of type string', function () {
    $asUserDetails = new AsUserDetails();

    expect(fn () => $asUserDetails->get(UserDetailFactory::new()->makeOne(), 'user_details', [], []))
        ->toThrow(InvalidArgumentException::class, 'The type of the value argument must be string.');
});

test('get method throws InvalidArgumentException when passed value is not a valid JSON', function () {
    $asUserDetails = new AsUserDetails();

    expect(fn () => $asUserDetails->get(UserDetailFactory::new()->makeOne(), 'user_details', '^435od09asd asd0as0das0d', []))
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
    $userDetailsArray = [
        'name' => 'Mike Doe',
        'email' => 'mike@doe.test',
        'address' => $address,
        'taxNumber' => 'US-TAX-9912',
        'phone' => '0023-234-213',
    ];
    $userDetails = UserDetails::fromArray($userDetailsArray);
    $asUserDetails = new AsUserDetails();

    $result = $asUserDetails->set(UserDetailFactory::new()->makeOne(), 'user_details', $userDetails, []);

    expect($result)->toBe([
        'user_details' => json_encode($userDetailsArray),
    ]);
});

test('set method throws InvalidArgumentException when passed value is not an instance of UserDetails', function () {
    $asUserDetails = new AsUserDetails();

    expect(fn () => $asUserDetails->set(UserDetailFactory::new()->makeOne(), 'user_details', [], []))
        ->toThrow(InvalidArgumentException::class, 'The given value is not a UserDetails instance.');
});
