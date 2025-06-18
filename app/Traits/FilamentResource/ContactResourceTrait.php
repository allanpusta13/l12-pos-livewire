<?php

namespace App\Traits\FilamentResource;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

trait ContactResourceTrait
{
    use SoftDeleteTrait, TableTrait;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true),
                PhoneInput::make('phone'),
                Forms\Components\TextInput::make('address'),
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                PhoneColumn::make('phone'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions(static::getDefaultTableActions(softDelete: true))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
