<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages;
use App\Filament\Clusters\Products\Resources\ProductResource\RelationManagers\BatchesRelationManager;
use App\Models\Product;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    use SoftDeleteTrait, TableTrait;

    private static $skuCounter = null;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Products::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema(static::getProductSchema()),
                Forms\Components\Section::make()
                    ->visible(function (Get $get) {
                        return $get('has_composition');
                    })
                    ->schema([
                        Forms\Components\Repeater::make('ingredients')
                            ->columns(3)
                            ->columnSpanFull()
                            ->schema(static::getIngredientSchema())
                            ->relationship(),
                    ]),
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
                // Todo: integrate transaction aggregation
                Tables\Columns\TextColumn::make('stocks')
                    ->label('Stocks'),
                Tables\Columns\TextColumn::make('unit.abbreviation')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_public')
                    ->label(__('product.is_public')),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('common.is_active')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('unit_id')
                    ->relationship('unit', 'name'),
            ])
            ->actions(static::getDefaultTableActions(softDelete: true))
            ->bulkActions(static::getDefaultTableBulkActions(softDelete: true));
    }

    public static function getRelations(): array
    {
        return [
            BatchesRelationManager::class,
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
                Forms\Components\TextInput::make('sku')
                    ->numeric()
                    ->default(function (?Model $record) {
                        if (self::$skuCounter === null) {
                            self::$skuCounter = Product::max('sku') ?? 99999;

                            return ++self::$skuCounter;
                        }
                    })
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
                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->readOnly(function (Get $get) {
                        return $get('has_composition');
                    })
                    ->default(0)
                    ->inputMode('decimal'),
            ]),
            Forms\Components\Split::make([
                Forms\Components\Toggle::make('has_composition')
                    ->inline(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $set('manage_stock', false);
                        }

                        if ($state) {
                            $set('ingredients', null);
                        }
                    })
                    ->reactive(),
                Forms\Components\Toggle::make('manage_stock')
                    ->hidden(function (Get $get) {
                        return $get('has_composition');
                    })
                    ->inline(false),
            ]),
        ];
    }

    public static function getIngredientSchema(): array
    {
        return [
            Forms\Components\Select::make('ingredient_id')
                ->relationship('ingredient', 'name', modifyQueryUsing: function (Builder $query) {
                    return $query->active();
                })
                ->live()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $product = Product::find($state);
                    $set('quantity', 1);
                    $set('cost', static::computeIngredientCost(1, $product->cost));

                    static::computeTotalCost($get('../../ingredients'), $set);
                })
                ->distinct()
                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                ->required(),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->live()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $product = Product::find($get('ingredient_id'));
                    $set('cost', static::computeIngredientCost($state, $product->cost));
                    static::computeTotalCost($get('../../ingredients'), $set);
                })
                ->required(),
            Forms\Components\TextInput::make('cost')
                ->readOnly()
                ->afterStateHydrated(function (?Model $record, Set $set, Get $get) {
                    $set('cost', static::computeIngredientCost($get('quantity'), $record?->ingredient->cost ?? 0));
                    static::computeTotalCost($get('../../ingredients'), $set);
                })
                ->inputMode('decimal')
                ->numeric(),
        ];
    }

    public static function computeIngredientCost($quantity, $cost)
    {
        return $quantity * $cost;
    }

    public static function computeTotalCost($ingredients, $set)
    {
        $total_cost = collect($ingredients)->sum('cost');
        $set('../../cost', $total_cost);
    }
}
