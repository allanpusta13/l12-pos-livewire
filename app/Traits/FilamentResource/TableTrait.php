<?php

namespace App\Traits\FilamentResource;

use Filament\Tables;

trait TableTrait
{
    public static function getDefaultTableActions($softDelete = false): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            ...($softDelete ? [
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ] : []),
        ];
    }

    public static function getDefaultTableBulkActions($softDelete = false): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ...($softDelete ? [
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ] : []),
            ]),
        ];
    }
}
