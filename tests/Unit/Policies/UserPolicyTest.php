<?php

declare(strict_types=1);

use App\Policies\UserPolicy;

it('allows creating a user', function () {
    $userPolicy = new UserPolicy();

    expect($userPolicy->create(null))->toBeTrue();
});
