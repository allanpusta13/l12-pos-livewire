<?php

namespace App\Models;

use App\Traits\Model\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasActiveScope;

    protected $fillable = [
        'name',
        'description',
        'price',
        'unit_id',
        'is_ingredient',
        'is_active',
    ];

    protected $casts = [
        'is_ingredient' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeIngredient($query)
    {
        return $query->where('is_ingredient', true);
    }
}
