<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Region;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidRegionCode implements ValidationRule
{
    public const int CODE_MIN_LENGTH = 3;

    public const int CODE_MAX_LENGTH = 6;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string.');

            return;
        }

        if (mb_strlen($value) < self::CODE_MIN_LENGTH) {
            $fail('The :attribute field must be at least '.self::CODE_MIN_LENGTH.' characters.');

            return;
        }

        if (mb_strlen($value) > self::CODE_MAX_LENGTH) {
            $fail('The :attribute field must not be greater than '.self::CODE_MAX_LENGTH.' characters.');

            return;
        }

        if (! preg_match('/^[A-Z]{2}-[A-Z0-9]{2,3}$/', $value)) {
            $fail('The :attribute field format is invalid.');

            return;
        }

        if (! $this->isValidRegionCode($value)) {
            $fail('The selected :attribute is invalid.');
        }
    }

    private function isValidRegionCode(string $code): bool
    {
        return in_array(
            $code,
            Region::query()
                ->pluck('code')
                ->map(static fn (string $code) => mb_strtoupper($code))
                ->all()
        );
    }
}
