<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function create(?User $user): bool
    {
        return true;
    }
}
