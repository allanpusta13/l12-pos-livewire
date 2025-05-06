<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use App\Traits\Model\HasActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasActiveScope, SoftDeletes;

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
}
