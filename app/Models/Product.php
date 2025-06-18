<?php

namespace App\Models;

use App\Traits\Model\HasActiveScope;
use App\Traits\Model\HasPublicScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'cost',
        'unit_id',
        'has_composition',
        'is_active',
        'is_public',
        'manage_stock',
    ];

    protected $casts = [
        'has_composition' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'manage_stock' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function stocks(): Attribute
    {
        return Attribute::make(get: fn () => $this->batches->sum('stocks') ?? 0);
    }
}
