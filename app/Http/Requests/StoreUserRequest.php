<?php

declare(strict_types=1);

namespace App\Http\Requests;

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

final class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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
        ];
    }
}
