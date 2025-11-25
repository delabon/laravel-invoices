<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Address
{
    public function __construct(
        public string $countryCode,
        public string $regionCode,
        public string $city,
        public string $zip,
        public string $lineOne,
        public ?string $lineTwo,
    ) {
        if (empty($this->countryCode)) {
            throw new InvalidArgumentException('The country code property is empty.');
        }

        if (empty($this->regionCode)) {
            throw new InvalidArgumentException('The region code property is empty.');
        }

        if (empty($this->city)) {
            throw new InvalidArgumentException('The city property is empty.');
        }

        if (empty($this->zip)) {
            throw new InvalidArgumentException('The zip property is empty.');
        }

        if (empty($this->lineOne)) {
            throw new InvalidArgumentException('The line one property is empty.');
        }

        if (preg_match('/[^a-z0-9-]/i', $this->zip)) {
            throw new InvalidArgumentException('The zip property is invalid.');
        }

        // TODO: complete country code and region code validation with real world codes
    }

    /**
     * @param array<string, null|string> $data
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
