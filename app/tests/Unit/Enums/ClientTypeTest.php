<?php

declare(strict_types=1);

use App\Enums\ClientType;

test('to array', function () {
    expect(ClientType::toArray())->toBe([
        'company',
        'person',
    ]);
});

