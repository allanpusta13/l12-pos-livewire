<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;

class StockManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Inventory';

    // protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'inventory';

    // protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
