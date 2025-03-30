<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LocationTypeEnum: string implements HasLabel
{
    case STORE = 'store';
    case WAREHOUSE = 'warehouse';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STORE => __('location.type.store'),
            self::WAREHOUSE => __('location.type.warehouse'),
        };
    }
}
