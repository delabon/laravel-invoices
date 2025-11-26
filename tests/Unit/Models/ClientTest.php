<?php

declare(strict_types=1);

use App\Models\User;
use App\ValueObjects\Address;
use Database\Factories\ClientFactory;
use Database\Factories\UserFactory;

test('to array', function () {
    $client = ClientFactory::new()->create();

    expect($client->refresh()->toArray())->toHaveKeys([
        'id',
        'user_id',
        'name',
        'address',
        'created_at',
        'updated_at',
    ]);
});

it('casts an address json string to an Address object', function () {
    $client = ClientFactory::new()->create();

    expect($client->address)->toBeInstanceOf(Address::class);
});

it('belongs to a user', function () {
    $user = UserFactory::new()->create([
        'email' => 'test8763@test.cc',
    ]);
    $addressData = [
        'countryCode' => 'US',
        'regionCode' => 'US-NY',
        'city' => 'New York City',
        'zip' => '10001',
        'lineOne' => 'main street 1234',
    ];
    $client = $user->clients()->create([
        'name' => 'Client A',
        'address' => Address::fromArray($addressData),
    ]);

    expect($client->user->id)->toBe($user->id)
        ->and($client->user)->toBeInstanceOf(User::class);
});
