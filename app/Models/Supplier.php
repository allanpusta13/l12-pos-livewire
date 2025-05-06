<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use App\Traits\Model\HasActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    protected $table = 'contacts';

    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasActiveScope, HasFactory, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
        'type' => ContactTypeEnum::class,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('supplierFlag', function (Builder $builder) {
            $builder->where('type', ContactTypeEnum::SUPPLIER->value);
        });

        static::creating(function (Supplier $customer) {
            $customer->type = ContactTypeEnum::SUPPLIER;
        });
    }
}
