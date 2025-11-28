<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObjects\Address;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidAddressLine implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string.');

            return;
        }

        if (strlen($value) > Address::LINE_MAX_LENGTH) {
            $fail('The :attribute field must not be greater than '.Address::LINE_MAX_LENGTH.' characters.');
        }
    }
}
