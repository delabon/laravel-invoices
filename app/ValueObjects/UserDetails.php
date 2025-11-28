<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\User;
use App\Models\UserDetail;
use App\Rules\ValidName;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Throwable;

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
                    new ValidName(),
                ],
                'email' => [
                    'required',
                    'email',
                ],
                'taxNumber' => [
                    'required',
                    'string',
                    'max:'.UserDetail::MAX_TAX_NUMBER_LENGTH,
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:'.UserDetail::MAX_PHONE_LENGTH,
                ],
            ]
        );
    }

    public static function fromUser(User $user): self
    {
        /** @var UserDetail $userDetails */
        $userDetails = $user->details;

        return new self(
            name: $user->name,
            email: $user->email,
            address: $userDetails->address,
            taxNumber: $userDetails->tax_number,
            phone: $userDetails->phone
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
            : '';
        $taxNumber = isset($data['taxNumber']) && is_string($data['taxNumber'])
            ? $data['taxNumber']
            : '';
        $phone = isset($data['phone']) && is_string($data['phone'])
            ? $data['phone']
            : '';

        throw_if(
            ! (isset($data['address']) && $data['address'] instanceof Address),
            new InvalidArgumentException('The address parameter must be an instance of Address value object.')
        );

        return new self(
            name: $name,
            email: $email,
            address: $data['address'],
            taxNumber: $taxNumber,
            phone: $phone,
        );
    }
}
