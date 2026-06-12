<?php

namespace App\Filament\Customer\Pages;

use App\Filament\Customer\Widgets\DepositChart;
use Filament\Pages\Page;

class DepositReport extends Page
{
    protected static ?string $title = 'Laporan Deposit';
    protected ?string $heading = 'Laporan Deposit';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static bool $shouldRegisterNavigation = false; // We registered it manually in PanelProvider
    
    protected string $view = 'filament.customer.pages.deposit-report';

    protected function getHeaderWidgets(): array
    {
        return [
            DepositChart::class,
        ];
    }
}
