<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserDetail extends Model
{
    protected $fillable = [
        'address',
        'tax_number',
        'phone',
    ];

    protected $casts = [
        'address' => AsAddress::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
