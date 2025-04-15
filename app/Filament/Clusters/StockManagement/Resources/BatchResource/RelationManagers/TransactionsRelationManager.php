<?php

namespace App\Filament\Clusters\StockManagement\Resources\BatchResource\RelationManagers;

use App\Enums\InventoryTransactionTypeEnum;
use App\Traits\FilamentResource\SoftDeleteTrait;
use App\Traits\FilamentResource\TableTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    use SoftDeleteTrait, TableTrait;

    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('unit_id')
                    ->default($this->getOwnerRecord()->product->unit_id),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(InventoryTransactionTypeEnum::class)
                    ->live()
                    ->reactive()
                    // TODO: setup sales and purchase selection depending on type
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    // ->header()
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->numeric()
                            // ->header('')
                            ->label('Total:'),
                    ),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Transaction'),
            ])
            ->actions(static::getDefaultTableActions(softDelete: true))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
