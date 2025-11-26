<?php

declare(strict_types=1);

use App\Models\Country;

test('to array', function () {
    $country = Country::query()
        ->where('id', 'us')
        ->first();

    expect($country->toArray())->toBe([
        'id' => 'us',
        'calling_code' => '1',
        'capital_city' => 'Washington',
        'code_2' => 'us',
        'code_3' => 'usa',
        'continent_id' => 'na',
        'currency_id' => 'usd',
        'flag' => '🇺🇸',
        'name' => 'United States',
    ]);
});
