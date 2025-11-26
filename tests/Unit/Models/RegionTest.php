<?php

declare(strict_types=1);

use App\Models\Region;

test('to array', function () {
    $region = Region::query()
        ->where('country_id', 'us')
        ->first();

    expect($region->toArray())->toBe([
        'id' => 'us-ak',
        'code' => 'us-ak',
        'country_id' => 'us',
        'name' => 'Alaska',
    ]);
});
