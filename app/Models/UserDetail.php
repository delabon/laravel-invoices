<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserDetail extends Model
{
    public const int MAX_TAX_NUMBER_LENGTH = 50;
    public const int MAX_PHONE_LENGTH = 20;

    protected $fillable = [
        'address',
        'tax_number',
        'phone',
    ];

    protected $casts = [
        'address' => AsAddress::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
