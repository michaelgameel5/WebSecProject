<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingAddress extends Model
{
    protected $fillable = [
        'card_id',
        'street_address',
        'city',
        'state',
        'postal_code',
        'country'
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
} 