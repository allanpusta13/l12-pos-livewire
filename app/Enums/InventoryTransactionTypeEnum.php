<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InventoryTransactionTypeEnum: string implements HasLabel
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
    case ADJUSTMENT = 'adjustment';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PURCHASE => __('inventory_transaction.purchase'),
            self::SALE => __('inventory_transaction.sale'),
            self::ADJUSTMENT => __('inventory_transaction.adjustment'),
        };
    }
}
