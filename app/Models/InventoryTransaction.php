<?php

namespace App\Models;

use App\Enums\InventoryTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'unit_id',
        'quantity',
        'type',
        'notes',
    ];

    protected $casts = [
        'type' => InventoryTransactionTypeEnum::class,
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
