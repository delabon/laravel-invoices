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
        public Address $address,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $taxNumber = null
    ) {
        Validator::validate(
            $this->toArray(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:'.Client::NAME_MAX_LENGTH,
                ],
                'email' => [
                    'nullable',
                    'email',
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:'.Client::PHONE_MAX_LENGTH,
                ],
                'taxNumber' => [
                    'nullable',
                    'string',
                    'max:'.Client::TAX_NUMBER_MAX_LENGTH,
                ],
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            address: $data['address'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            taxNumber: $data['taxNumber'] ?? $data['tax_number'] ?? null
        );
    }
}
