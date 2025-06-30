<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

test('to array', function () {
    $client = Client::factory()->create();

    expect($client->toArray())->toHaveKeys([
        'id',
        'user_id',
        'name',
        'type',
        'email',
        'address',
        'phone',
        'created_at',
        'updated_at',
    ]);
});

test('client must be unique', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create([
        'user_id' => $user->id,
        'email' => 'john@example.com'
    ]);

    $client2 = Client::factory()->create([
        'user_id' => $user->id,
        'email' => 'john@example.com'
    ]);
})->throws(UniqueConstraintViolationException::class);
