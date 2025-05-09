<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'created_at',
        'updated_at',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}