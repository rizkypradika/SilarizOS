<?php

namespace App\Filament\Customer\Resources\Deposits\Pages;

use App\Filament\Customer\Resources\Deposits\DepositResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeposits extends ListRecords
{
    protected static ?string $title = 'Daftar Deposit';

    protected static string $resource = DepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
