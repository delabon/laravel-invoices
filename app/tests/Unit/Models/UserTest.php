<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Invoice;
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

    $clients = Client::factory(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->clients)->toHaveCount(3)
        ->and($user->clients->first())->toBeInstanceOf(Client::class)
        ->and($user->clients->first()->id)->toBe($clients[0]->id);
});

it('has many invoices', function () {
    $user = User::factory()->create();

    $invoices = Invoice::factory(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->invoices)->toHaveCount(3)
        ->and($user->invoices->first())->toBeInstanceOf(Invoice::class)
        ->and($user->invoices->first()->id)->toBe($invoices[0]->id);
});
