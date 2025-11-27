<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Client extends Model
{
    public const int NAME_MAX_LENGTH = 255;
    public const int PHONE_MAX_LENGTH = 20;
    public const int TAX_NUMBER_MAX_LENGTH = 50;

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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
