<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Client extends Model
{
    public const int MAX_NAME_LENGTH = 255;

    protected $fillable = [
        'name',
        'address',
    ];

    protected $casts = [
        'address' => AsAddress::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
