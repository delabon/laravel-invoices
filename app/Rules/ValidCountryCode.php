<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Country;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

final class ValidCountryCode implements ValidationRule
{
    public const int CODE_LENGTH = 2;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string.');

            return;
        }

        if (mb_strlen($value) !== self::CODE_LENGTH) {
            $fail('The :attribute field must be '.self::CODE_LENGTH.' characters.');

            return;
        }

        if (! preg_match('/^[A-Z]{2}$/', $value)) {
            $fail('The :attribute field format is invalid.');

            return;
        }

        if (! $this->isValidCountryCode($value)) {
            $fail('The selected :attribute is invalid.');
        }
    }

    private function isValidCountryCode(string $code): bool
    {
        return in_array(
            $code,
            Cache::remember('country_codes', now()->addMonth(), function () {
                return Country::query()
                    ->pluck('code_2')
                    /** @phpstan-ignore argument.type */
                    ->map(static fn (string $code) => mb_strtoupper($code))
                    ->all();
            })
        );
    }
}
