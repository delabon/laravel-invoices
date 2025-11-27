<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\UserDetail;
use Database\Factories\ClientFactory;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserFactory;

test('to array', function () {
    $user = UserFactory::new()->create();

    expect($user->refresh()->toArray())->toHaveKeys([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

it('has many clients', function () {
    $user = UserFactory::new()->create([
        'email' => 'test8763@test.cc',
    ]);
    $clients = ClientFactory::times(3)->create([
        'user_id' => $user->id,
    ]);

    expect($clients->count())->toBe(3);

    foreach ($clients as $client) {
        expect($client->user->id)->toBe($user->id)
            ->and($client)->toBeInstanceOf(Client::class);
    }
});

it('has one detail', function () {
    $user = UserFactory::new()->create([
        'email' => 'test8763@test.cc',
    ]);
    $detail = UserDetailFactory::new()->create([
        'user_id' => $user->id,
    ]);

    expect($user->details)->toBeInstanceOf(UserDetail::class)
        ->and($user->details->id)->toBe($detail->id);
});
