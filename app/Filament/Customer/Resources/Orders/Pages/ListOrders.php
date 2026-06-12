<?php

namespace App\Filament\Customer\Resources\Orders\Pages;

use App\Filament\Customer\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static ?string $title = 'Daftar Pesanan';

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions
        ];
    }
}
