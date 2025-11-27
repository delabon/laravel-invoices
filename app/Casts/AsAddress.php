<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\Address;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements  CastsAttributes<Address, Address>
 */
final class AsAddress implements CastsAttributes
{
    /**
     * @throws InvalidArgumentException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Address
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The type of the value argument must be string.');
        }

        if (! json_validate($value)) {
            throw new InvalidArgumentException('The type of the value argument must be a valid JSON.');
        }

        /** @phpstan-ignore argument.type */
        return Address::fromArray(json_decode($value, true));
    }

    /**
     * @return array<string, false|string>
     *
     * @throws InvalidArgumentException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof Address) {
            throw new InvalidArgumentException('The given value is not an Address instance.');
        }

        return [
            $key => json_encode($value->toArray()),
        ];
    }
}
