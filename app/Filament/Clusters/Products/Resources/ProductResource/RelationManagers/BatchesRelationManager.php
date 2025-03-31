<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\RelationManagers;

use App\Filament\Clusters\Products\Resources\ProductResource\Pages\ViewProduct;
use App\Filament\Clusters\StockManagement\Resources\BatchResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'batches';

    public function form(Form $form): Form
    {
        return BatchResource::form($form);
    }

    public function table(Table $table): Table
    {
        return BatchResource::table($table);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass == ViewProduct::class;
    }
}
