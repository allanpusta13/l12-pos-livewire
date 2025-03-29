<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    use SoftDeleteTrait, TableTrait;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Products::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema(static::getProductSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Todo: add product image
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.abbreviation')
                    ->searchable()
                    ->sortable(),
                // Todo: integrate transaction aggregation
                Tables\Columns\TextColumn::make('stocks'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getProductSchema(): array
    {
        return [
            Forms\Components\Split::make([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
            ]),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
            Forms\Components\Split::make([
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->default(0)
                    ->inputMode('decimal'),
                Forms\Components\Toggle::make('is_ingredient')
                    ->inline(false),
            ]),
        ];
    }
}
