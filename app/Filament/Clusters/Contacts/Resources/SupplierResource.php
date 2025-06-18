<?php

namespace App\Filament\Clusters\Contacts\Resources;

use App\Filament\Clusters\Contacts;
use App\Filament\Clusters\Contacts\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use App\Traits\FilamentResource\ContactResourceTrait;
use Filament\Resources\Resource;

class SupplierResource extends Resource
{
    use ContactResourceTrait;

    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Contacts::class;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSuppliers::route('/'),
        ];
    }
}
