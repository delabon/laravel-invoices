<?php

declare(strict_types=1);

namespace App\Enums;

enum ClientType: string
{
    case Company = 'company';
    case Person = 'person';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn ($case) => $case->value,
            self::cases()
        );
    }
}
