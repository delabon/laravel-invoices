<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\Address;
use App\ValueObjects\ClientDetails;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class AsClientDetails implements CastsAttributes
{
    /**
     * @throws InvalidArgumentException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ClientDetails
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The type of the value argument must be string.');
        }

        if (! json_validate($value)) {
            throw new InvalidArgumentException('The type of the value argument must be a valid JSON.');
        }

        $clientDetailsData = json_decode($value, true);
        $address = $clientDetailsData['address'] ?? null;

        if (is_array($clientDetailsData['address'])) {
            $address = Address::fromArray($clientDetailsData['address']);
        }

        $clientDetailsData['address'] = $address;

        return ClientDetails::fromArray($clientDetailsData);
    }

    /**
     * @return array<string, false|string>
     * @throws InvalidArgumentException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof ClientDetails) {
            throw new InvalidArgumentException('The given value is not a ClientDetails instance.');
        }

        return [
            $key => json_encode($value->toArray()),
        ];
    }
}
