<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use App\Traits\Model\HasActiveScope;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasActiveScope;

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
