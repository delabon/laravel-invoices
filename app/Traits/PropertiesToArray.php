<?php

declare(strict_types=1);

namespace App\Traits;

trait PropertiesToArray
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
