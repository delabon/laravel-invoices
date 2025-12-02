<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidCity implements ValidationRule
{
    public const int MAX_LENGTH = 50;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string.');

            return;
        }

        if (mb_strlen($value) > self::MAX_LENGTH) {
            $fail('The :attribute field must not be greater than '.self::MAX_LENGTH.' characters.');
        }
    }
}
