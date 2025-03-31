<?php

namespace App\Models;

use App\Traits\Model\HasActiveScope;
use App\Traits\Model\HasPublicScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasActiveScope, HasFactory, HasPublicScope, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'unit_id',
        'is_ingredient',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'is_ingredient' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeIngredient($query)
    {
        return $query->where('is_ingredient', true);
    }

    public function batches()
    {
        return $this->morphMany(Batch::class, 'batchable');
    }
}
