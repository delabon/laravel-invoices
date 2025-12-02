<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class NewUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $phone,
        public string $taxNumber,
        public string $countryCode,
        public string $regionCode,
        public string $city,
        public string $zip,
        public string $lineOne,
        public ?string $lineTwo = null
    ) {}
}
