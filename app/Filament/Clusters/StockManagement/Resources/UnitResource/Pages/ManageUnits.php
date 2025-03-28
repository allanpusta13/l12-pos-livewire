<?php

namespace App\Filament\Clusters\StockManagement\Resources\UnitResource\Pages;

use App\Filament\Clusters\StockManagement\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUnits extends ManageRecords
{
    protected static string $resource = UnitResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
