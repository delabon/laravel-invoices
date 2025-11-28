<?php

declare(strict_types=1);

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
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

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
