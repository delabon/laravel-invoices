<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsClientDetails;
use App\Casts\AsUserDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Invoice extends Model
{
    protected $fillable = [
        'client_details',
        'user_details',
        'uid',
        'issued_at',
        'subtotal',
        'tax',
        'total',
    ];

    protected $casts = [
        'client_details' => AsClientDetails::class,
        'user_details' => AsUserDetails::class,
        'issued_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
