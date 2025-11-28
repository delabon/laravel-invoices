<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Rules\ValidCity;
use App\Rules\ValidCountryCode;
use App\Rules\ValidRegionCode;
use App\Rules\ValidZip;
use App\Traits\PropertiesToArray;
use Illuminate\Support\Facades\Validator;

final readonly class Address
{
    use PropertiesToArray;

    public const int CITY_MAX_LENGTH = 50;

    public const int ZIP_MAX_LENGTH = 20;

    public const int MAX_LINE_LENGTH = 255;

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
