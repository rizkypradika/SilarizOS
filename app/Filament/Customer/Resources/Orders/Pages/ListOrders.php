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

    public function getTabs(): array
    {
        $userId = auth()->id();
        return [
            'Semua' => \Filament\Schemas\Components\Tabs\Tab::make('Semua')
                ->badge(\App\Models\Order::where('user_id', $userId)->count())
                ->badgeColor('primary')
                ->icon('heroicon-m-list-bullet'),
            'Menunggu' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(\App\Models\Order::where('user_id', $userId)->where('status', 'pending')->count())
                ->badgeColor('warning')
                ->icon('heroicon-m-clock'),
            'Berhasil' => \Filament\Schemas\Components\Tabs\Tab::make('Berhasil')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed'))
                ->badge(\App\Models\Order::where('user_id', $userId)->where('status', 'completed')->count())
                ->badgeColor('success')
                ->icon('heroicon-m-check-circle'),
            'Dibatalkan' => \Filament\Schemas\Components\Tabs\Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'cancelled'))
                ->badge(\App\Models\Order::where('user_id', $userId)->where('status', 'cancelled')->count())
                ->badgeColor('danger')
                ->icon('heroicon-m-x-circle'),
        ];
    }
}
