<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SaleStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => __('sale.status.pending'),
            self::PROCESSING => __('sale.status.processing'),
            self::PAID => __('sale.status.paid'),
            self::CANCELED => __('sale.status.canceled'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::PROCESSING => 'heroicon-o-clock',
            self::PAID => 'heroicon-o-check-circle',
            self::CANCELED => 'heroicon-o-x-circle',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'info',
            self::PROCESSING => 'warning',
            self::PAID => 'success',
            self::CANCELED => 'danger',
        };
    }
}
