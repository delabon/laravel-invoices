<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\User;
use App\Models\UserDetail;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;

final readonly class UserDetails
{
    use PropertiesToArray;

    public function __construct(
        public string $name,
        public string $email,
        public Address $address,
        public string $taxNumber,
        public string $phone
    ) {
        Validator::validate(
            $this->toArray(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:' . User::MAX_NAME_LENGTH,
                ],
                'email' => [
                    'required',
                    'email',
                ],
                'taxNumber' => [
                    'required',
                    'string',
                    'max:' . UserDetail::MAX_TAX_NUMBER_LENGTH,
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:' . UserDetail::MAX_PHONE_LENGTH,
                ]
            ]
        );
    }

    public static function fromUser(User $user): self
    {
        $userDetails = $user->details;

        return new self(
            name: $user->name,
            email: $user->email,
            address: $userDetails->address,
            taxNumber: $userDetails->tax_number,
            phone: $userDetails->phone,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            address: $data['address'] ?? null,
            taxNumber: $data['taxNumber'] ?? '',
            phone: $data['phone'] ?? '',
        );
    }
}
