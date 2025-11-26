<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\Client;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;

final readonly class ClientDetails
{
    use PropertiesToArray;

    public function __construct(
        public string $name,
        public Address $address
    ) {
        Validator::validate(
            $this->toArray(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:' . Client::MAX_NAME_LENGTH,
                ],
            ]
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            address: $data['address'] ?? null,
        );
    }
}
