<?php

namespace App\Filament\Clusters\StockManagement\Resources;

use App\Filament\Clusters\StockManagement;
use App\Filament\Clusters\StockManagement\Resources\UnitResource\Pages;
use App\Models\Unit;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    use SoftDeleteTrait, TableTrait;

    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = StockManagement::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required(),
                    Forms\Components\TextInput::make('symbol')
                        ->label('Symbol'),
                ]),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Description'),
                Forms\Components\TextInput::make('abbreviation')
                    ->label('Abbreviation'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('abbreviation')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions(static::getDefaultTableActions(softDelete: true))
            ->bulkActions(static::getDefaultTableBulkActions(softDelete: true));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUnits::route('/'),
        ];
    }
}
