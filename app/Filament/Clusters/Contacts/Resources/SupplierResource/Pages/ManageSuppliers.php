<?php

namespace App\Filament\Clusters\Contacts\Resources\SupplierResource\Pages;

use App\Filament\Clusters\Contacts\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSuppliers extends ManageRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
