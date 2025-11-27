<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\Country;
use App\Models\Region;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final readonly class Address
{
    use PropertiesToArray;

    private const int COUNTRY_CODE_LENGTH = 2;

    private const int MIN_REGION_CODE_LENGTH = 3;

    private const int MAX_REGION_CODE_LENGTH = 6;

    private const int MAX_CITY_LENGTH = 50;

    private const int MAX_ZIP_LENGTH = 20;

    private const int MAX_LINE_LENGTH = 255;

    public function __construct(
        public string $countryCode,
        public string $regionCode,
        public string $city,
        public string $zip,
        public string $lineOne,
        public ?string $lineTwo,
    ) {
        Validator::validate(
            $this->toArray(),
            [
                'countryCode' => [
                    'required',
                    'size:'.self::COUNTRY_CODE_LENGTH,
                    'regex:/^[a-z]{2}$/i',
                    Rule::in(Country::query()->pluck('code_2')->map(static fn (string $code) => mb_strtoupper($code))->all()),
                ],
                'regionCode' => [
                    'required',
                    'min:'.self::MIN_REGION_CODE_LENGTH,
                    'max:'.self::MAX_REGION_CODE_LENGTH,
                    'regex:/^[a-z]{2}-[a-z0-9]{2,3}$/i',
                    Rule::in(Region::query()->pluck('code')->map(static fn (string $code) => mb_strtoupper($code))->all()),
                ],
                'city' => [
                    'required',
                    'max:'.self::MAX_CITY_LENGTH,
                ],
                'zip' => [
                    'required',
                    'max:'.self::MAX_ZIP_LENGTH,
                    'regex:/^[a-z0-9][a-z0-9-]+?[a-z0-9]$/i',
                ],
                'lineOne' => [
                    'required',
                    'max:'.self::MAX_LINE_LENGTH,
                ],
                'lineTwo' => [
                    'nullable',
                    'string',
                    'max:'.self::MAX_LINE_LENGTH,
                ],
            ]
        );
    }

    /**
     * @param  array<string, null|string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            countryCode: $data['countryCode'] ?? '',
            regionCode: $data['regionCode'] ?? '',
            city: $data['city'] ?? '',
            zip: $data['zip'] ?? '',
            lineOne: $data['lineOne'] ?? '',
            lineTwo: $data['lineTwo'] ?? null,
        );
    }
}
