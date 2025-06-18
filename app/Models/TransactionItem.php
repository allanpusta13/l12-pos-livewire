<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionItemFactory> */
    use HasFactory;

    protected $fillable = [
        'transactionable_id',
        'transactionable_type',
        'batch_id',
        'unit_id',
        'quantity',
        'price',
        'notes',
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
