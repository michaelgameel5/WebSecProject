<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'card_number',
        'expiry_date',
        'cvv',
        'credit_balance',
        'is_active'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'credit_balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(BillingAddress::class);
    }
} 