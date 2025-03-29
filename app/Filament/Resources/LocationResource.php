<?php

namespace App\Filament\Resources;

use App\Enums\LocationTypeEnum;
use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    use SoftDeleteTrait, TableTrait;

    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getLocationSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('common.is_active')),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('/{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getLocationSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required(),
            Forms\Components\Select::make('type')
                ->options(LocationTypeEnum::class)
                ->required(),
            Forms\Components\Textarea::make('address')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('city'),
        ];
    }
}
