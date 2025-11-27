<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\Address;
use App\ValueObjects\UserDetails;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements  CastsAttributes<UserDetails, UserDetails>
 */
final class AsUserDetails implements CastsAttributes
{
    /**
     * @throws InvalidArgumentException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): UserDetails
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The type of the value argument must be string.');
        }

        if (! json_validate($value)) {
            throw new InvalidArgumentException('The type of the value argument must be a valid JSON.');
        }

        /** @var array<string, mixed> $userDetailsData */
        $userDetailsData = json_decode($value, true);
        $address = $userDetailsData['address'] ?? null;

        if (is_array($address)) {
            /** @phpstan-ignore argument.type */
            $address = Address::fromArray($address);
        }

        $userDetailsData['address'] = $address;

        return UserDetails::fromArray($userDetailsData);
    }

    /**
     * @return array<string, false|string>
     *
     * @throws InvalidArgumentException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof UserDetails) {
            throw new InvalidArgumentException('The given value is not a UserDetails instance.');
        }

        return [
            $key => json_encode($value->toArray()),
        ];
    }
}
