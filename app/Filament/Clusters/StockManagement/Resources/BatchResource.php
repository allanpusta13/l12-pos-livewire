<?php

namespace App\Filament\Clusters\StockManagement\Resources;

use App\Enums\InventoryTransactionTypeEnum;
use App\Filament\Clusters\StockManagement;
use App\Filament\Clusters\StockManagement\Resources\BatchResource\Pages;
use App\Models\Batch;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BatchResource extends Resource
{
    private static $batchCounter = null;

    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = StockManagement::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema(static::getBatchSchema()),
                Forms\Components\Grid::make()
                    ->hiddenOn('edit')
                    ->schema([
                        Forms\Components\TextInput::make('transactions.0.quantity')
                            ->default(0)
                            ->numeric()
                            ->inputMode('decimal')
                            ->required(),
                        Forms\Components\Hidden::make('transactions.0.unit_id'),
                        Forms\Components\Hidden::make('transactions.0.type')
                            ->default(InventoryTransactionTypeEnum::ADJUSTMENT),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->color(function ($state) {
                        return $state->isPast() ? 'danger' : 'success';
                    })
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('transactions_sum_quantity')
                    ->label('Quantity')
                    ->sum('transactions', 'quantity')
                    ->action(Tables\Actions\Action::make('adjust')
                        ->form(function (Form $form) {
                            return $form
                                ->schema([
                                    Forms\Components\TextInput::make('quantity')
                                        ->numeric()
                                        ->step(1)
                                        ->inputMode('decimal')
                                        ->required(),
                                    Forms\Components\Textarea::make('notes'),
                                ]);
                        })
                        ->action(function (Model $record, array $data) {
                            $data['unit_id'] = $record->product->unit_id;
                            $data['type'] = InventoryTransactionTypeEnum::ADJUSTMENT->value;
                            $record->transactions()->create($data);
                        }), ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('id', 'desc')
            ->persistSortInSession();
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
            'index' => Pages\ManageBatches::route('/'),
        ];
    }

    public static function getBatchSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name', modifyQueryUsing: function (Builder $query) {
                    return $query->active()->where('manage_stock', true);
                })
                ->afterStateUpdated(function ($state, Set $set) {
                    $product = Product::find($state);
                    $set('transactions.0.unit_id', $product->unit_id);
                })
                ->live()
                ->reactive()
                ->required(),
            Forms\Components\Select::make('location_id')
                ->relationship('location', 'name', modifyQueryUsing: function (Builder $query) {
                    return $query->active();
                })
                ->required(),
            Forms\Components\TextInput::make('batch_number')
                ->default(function (?Model $record) {
                    if (self::$batchCounter === null) {
                        self::$batchCounter = Batch::max('batch_number') ?? 99999;

                        return ++self::$batchCounter;
                    }
                })
                ->required(),
            Forms\Components\DatePicker::make('expiry_date'),
        ];
    }
}
