<?php

declare(strict_types=1);

use App\DTOs\NewUserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Rules\ValidAddressLine;
use App\Rules\ValidCity;
use App\Rules\ValidCountryCode;
use App\Rules\ValidName;
use App\Rules\ValidPassword;
use App\Rules\ValidPhone;
use App\Rules\ValidRegionCode;
use App\Rules\ValidTaxNumber;
use App\Rules\ValidZip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

it('is an instance of FormRequest', function () {
    $request = new StoreUserRequest();

    expect($request)->toBeInstanceOf(FormRequest::class);
});

it('authorizes the request successfully', function () {
    Gate::shouldReceive('allows')
        ->once()
        ->with('create', User::class)
        ->andReturn(true);

    $request = new StoreUserRequest();

    expect($request->authorize())->toBeTrue();
});

it('returns the correct rules', function () {
    $request = new StoreUserRequest();

    expect($request->rules())->toEqual([
        'name' => [
            'required',
            new ValidName(),
        ],
        'email' => [
            'required',
            'email',
            'unique:users,email',
        ],
        'password' => [
            'required',
            new ValidPassword(),
        ],
        'password_confirmation' => [
            'required',
            'confirmed:password',
        ],
        'countryCode' => [
            'required',
            new ValidCountryCode(),
        ],
        'regionCode' => [
            'required',
            new ValidRegionCode(),
        ],
        'city' => [
            'required',
            new ValidCity(),
        ],
        'zip' => [
            'required',
            new ValidZip(),
        ],
        'lineOne' => [
            'required',
            new ValidAddressLine(),
        ],
        'lineTwo' => [
            'nullable',
            new ValidAddressLine(),
        ],
        'phone' => [
            'required',
            new ValidPhone(),
        ],
        'taxNumber' => [
            'required',
            new ValidTaxNumber(),
        ],
    ]);
});

test('toDto method returns a new instance of NewUserDto', function () {
    $request = new StoreUserRequest();
    $request->replace([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'countryCode' => 'US',
        'regionCode' => 'US-CA',
        'city' => 'Los Angeles',
        'zip' => '9003',
        'lineOne' => 'Main St 123',
        'lineTwo' => 'N453',
        'phone' => '123-4567-567',
        'taxNumber' => 'TAX-123-456',
    ]);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(NewUserDTO::class)
        ->and($dto->name)->toBe('Test User')
        ->and($dto->email)->toBe('test@example.com')
        ->and($dto->password)->toBe('password')
        ->and($dto->countryCode)->toBe('US')
        ->and($dto->regionCode)->toBe('US-CA')
        ->and($dto->city)->toBe('Los Angeles')
        ->and($dto->zip)->toBe('9003')
        ->and($dto->lineOne)->toBe('Main St 123')
        ->and($dto->lineTwo)->toBe('N453')
        ->and($dto->phone)->toBe('123-4567-567')
        ->and($dto->taxNumber)->toBe('TAX-123-456');
});
