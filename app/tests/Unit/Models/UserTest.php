<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;

test('to array', function () {
    $user = User::factory()->create();

    expect($user->toArray())->toHaveKeys([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ])
        ->not
        ->toHaveKey('passowrd')
        ->not
        ->toHaveKey('remember_token');
});

it('has many clients', function () {
    $user = User::factory()->create();

    $clients = Client::factory([
        'user_id' => $user->id,
    ])->create(3);

    expect($user->clients)->toHaveCount(3)
        ->and($user->clients->first())->toBeInstanceOf(Client::class)
        ->and($user->clients->first()->id)->toBe($clients[0]->id);
});
