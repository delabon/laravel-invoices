<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\DTOs\NewUserDTO;
use App\Mail\Registered;
use App\Models\User;
use App\ValueObjects\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

final class StoreUserAction
{
    /**
     * @throws Throwable
     */
    public function execute(NewUserDTO $dto): User
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
            ]);

            $user->details()->create([
                'address' => Address::fromArray([
                    'countryCode' => $dto->countryCode,
                    'regionCode' => $dto->regionCode,
                    'city' => $dto->city,
                    'zip' => $dto->zip,
                    'lineOne' => $dto->lineOne,
                    'lineTwo' => $dto->lineTwo,
                ]),
                'tax_number' => $dto->taxNumber,
                'phone' => $dto->phone,
            ]);

            DB::commit();

            Auth::login($user);

            dispatch(function () use ($user) {
                Mail::to($user->email)
                    ->sendNow(new Registered($user));
            });

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();

            throw new RuntimeException(
                message: 'Registration failed. Please try again later.',
                previous: $e
            );
        }
    }
}
