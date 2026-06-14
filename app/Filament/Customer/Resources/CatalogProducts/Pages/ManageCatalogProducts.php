<?php

namespace App\Filament\Customer\Resources\CatalogProducts\Pages;

use App\Filament\Customer\Resources\CatalogProducts\CatalogProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCatalogProducts extends ManageRecords
{
    protected static string $resource = CatalogProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
