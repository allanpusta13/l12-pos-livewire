<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContactTypeEnum: string implements HasLabel
{
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
    case EMPLOYEE = 'employee';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CUSTOMER => __('contact.customer'),
            self::SUPPLIER => __('contact.supplier'),
            self::EMPLOYEE => __('contact.employee'),
            self::OTHER => __('contact.other'),
        };
    }
}
