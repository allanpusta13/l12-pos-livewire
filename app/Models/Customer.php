<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use App\Traits\Model\HasActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    protected $table = 'contacts';

    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasActiveScope, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_active',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => ContactTypeEnum::class,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('customerFlag', function (Builder $builder) {
            $builder->where('type', ContactTypeEnum::CUSTOMER->value);
        });

        static::creating(function (Customer $customer) {
            $customer->type = ContactTypeEnum::CUSTOMER;
        });
    }
}
