<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Invoice;
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

    Client::factory()->create([
        'user_id' => $user->id,
        'email' => 'john@example.com',
    ]);

    Client::factory()->create([
        'user_id' => $user->id,
        'email' => 'john@example.com',
    ]);
})->throws(UniqueConstraintViolationException::class);

it('belongs to a user', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($client->user)->toBeInstanceOf(User::class)
        ->and($client->user->is($user))->toBeTrue();
});

it('has many invoices', function () {
    $client = Client::factory()->create();

    $invoices = Invoice::factory(3)->create([
        'client_id' => $client->id,
    ]);

    expect($client->invoices()->whereIn('id', $invoices->pluck('id')->toArray())->count())->toBe(3)
        ->and($client->invoices->first())->toBeInstanceOf(Invoice::class);
});
