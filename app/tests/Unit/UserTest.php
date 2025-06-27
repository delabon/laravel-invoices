<?php

declare(strict_types=1);

use App\Models\User;

test('to array', function () {
    $user = User::factory()->create();

    expect($user->toArray())->toHaveKeys([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at'
    ])
        ->not
        ->toHaveKey('passowrd')
        ->not
        ->toHaveKey('remember_token');
});
