<?php

declare(strict_types=1);

namespace App\Models;

use Squire\Models\Country as SquireCountry;

final class Country extends SquireCountry
{
    public const int CODE_LENGTH = 2;
}
