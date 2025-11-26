<?php

declare(strict_types=1);

use App\Models\Client;
use App\ValueObjects\ClientDetails;
use App\ValueObjects\UserDetails;
use Database\Factories\ClientFactory;
use Database\Factories\InvoiceFactory;
use Illuminate\Support\Carbon;

test('to array', function () {
    $invoice = InvoiceFactory::new()->create();

    expect($invoice->refresh()->toArray())->toHaveKeys([
        'id',
        'client_id',
        'client_details',
        'user_details',
        'uid',
        'issued_at',
        'subtotal',
        'tax',
        'total',
        'created_at',
        'updated_at',
    ]);
});

it('casts an client_details json string to an ClientDetails object', function () {
    $invoice = InvoiceFactory::new()->create();

    expect($invoice->client_details)->toBeInstanceOf(ClientDetails::class);
});

it('casts an user_details json string to an UserDetails object', function () {
    $invoice = InvoiceFactory::new()->create();

    expect($invoice->user_details)->toBeInstanceOf(UserDetails::class);
});

it('casts issued_at string to a Carbon datetime instance', function () {
    $invoice = InvoiceFactory::new()->create();

    expect($invoice->issued_at)->toBeInstanceOf(Carbon::class);
});

it('belongs to a client', function () {
    $client = ClientFactory::new()->create();

    $invoice = InvoiceFactory::new()->create(['client_id' => $client->id]);

    $invoice->load('client');

    expect($invoice->client)->toBeInstanceOf(Client::class)
        ->and($invoice->client->id)->toBe($client->id);
});
