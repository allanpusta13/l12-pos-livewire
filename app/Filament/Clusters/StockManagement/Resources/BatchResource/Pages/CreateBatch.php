<?php

namespace App\Filament\Clusters\StockManagement\Resources\BatchResource\Pages;

use App\Filament\Clusters\StockManagement\Resources\BatchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBatch extends CreateRecord
{
    protected static string $resource = BatchResource::class;
}
