<?php

declare(strict_types=1);

use App\Models\User;
use App\ValueObjects\Address;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserFactory;

test('to array', function () {
    $userDetail = UserDetailFactory::new()->create();

    expect($userDetail->refresh()->toArray())->toHaveKeys([
        'id',
        'user_id',
        'address',
        'tax_number',
        'phone',
        'created_at',
        'updated_at',
    ]);
});

it('casts address to an instance of Address', function () {
    $userDetail = UserDetailFactory::new()->create();

    expect($userDetail->address)->toBeInstanceOf(Address::class);
});

it('belongs to a user', function () {
    $user = UserFactory::new()->create([
        'email' => 'test8763@test.cc',
    ]);
    $userDetail = UserDetailFactory::new()->create([
        'user_id' => $user->id,
    ]);

    expect($userDetail->user)->toBeInstanceOf(User::class)
        ->and($userDetail->user->id)->toBe($user->id);
});
