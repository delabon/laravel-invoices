<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidZip implements ValidationRule
{
    public const int MAX_LENGTH = 20;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string.');

            return;
        }

        if (strlen($value) > self::MAX_LENGTH) {
            $fail('The :attribute field must not be greater than '.self::MAX_LENGTH.' characters.');

            return;
        }

        if (! preg_match('/^[a-z0-9][a-z0-9-]+?[a-z0-9]$/i', $value)) {
            $fail('The :attribute field format is invalid.');
        }
    }
}
