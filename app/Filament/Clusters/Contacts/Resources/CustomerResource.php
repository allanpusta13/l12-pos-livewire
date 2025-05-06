<?php

namespace App\Filament\Clusters\Contacts\Resources;

use App\Filament\Clusters\Contacts;
use App\Filament\Clusters\Contacts\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Traits\FilamentResource\ContactResourceTrait;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Resources\Resource;

class CustomerResource extends Resource
{
    use ContactResourceTrait, SoftDeleteTrait, TableTrait;

    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Contacts::class;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
