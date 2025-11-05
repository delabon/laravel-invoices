<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;

test('to array', function () {
    $invoice = Invoice::factory()->create();

    expect($invoice->toArray())->toHaveKeys([
        'id',
        'user_id',
        'client_id',
        'uid',
        'title',
        'currency',
        'tax_amount',
        'total_amount',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a user', function () {
    $user = User::factory()->create();

    $invoice = Invoice::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($invoice->user)->toBeInstanceOf(User::class)
        ->and($invoice->user->is($user))->toBeTrue();
});

it('belongs to a client', function () {
    $client = Client::factory()->create();

    $invoice = Invoice::factory()->create([
        'client_id' => $client->id,
    ]);

    expect($invoice->client)->toBeInstanceOf(Client::class)
        ->and($invoice->client->is($client))->toBeTrue();
});
