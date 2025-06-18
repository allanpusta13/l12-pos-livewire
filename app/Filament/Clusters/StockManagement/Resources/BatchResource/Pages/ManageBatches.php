<?php

namespace App\Filament\Clusters\StockManagement\Resources\BatchResource\Pages;

use App\Filament\Clusters\StockManagement\Resources\BatchResource;
use App\Models\Batch;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBatches extends ManageRecords
{
    protected static string $resource = BatchResource::class;

    protected static ?string $title = 'Stocks';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data) {
                    $batch = Batch::create(collect($data)->except('transactions')->toArray());
                    $batch->transactions()->create($data['transactions'][0]);
                }),
        ];
    }
}
