<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\Client;
use App\Rules\ValidName;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Throwable;

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
                    new ValidName(),
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
     *
     * @throws Throwable
     */
    public static function fromArray(array $data): self
    {
        $name = isset($data['name']) && is_string($data['name'])
            ? $data['name']
            : '';
        $email = isset($data['email']) && is_string($data['email'])
            ? $data['email']
            : null;
        $phone = isset($data['phone']) && is_string($data['phone'])
            ? $data['phone']
            : null;
        $taxNumber = isset($data['taxNumber']) && is_string($data['taxNumber'])
            ? $data['taxNumber']
            : null;

        throw_if(
            ! (isset($data['address']) && $data['address'] instanceof Address),
            new InvalidArgumentException('The address parameter must be an instance of Address value object.')
        );

        return new self(
            name: $name,
            address: $data['address'],
            email: $email,
            phone: $phone,
            taxNumber: $taxNumber
        );
    }
}
