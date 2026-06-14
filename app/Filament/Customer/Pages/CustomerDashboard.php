<?php

namespace App\Filament\Customer\Pages;

use App\Models\Deposit;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class CustomerDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public function getView(): string
    {
        return 'filament.customer.pages.customer-dashboard';
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        $totalOrdersThisMonth = Order::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price') ?? 0;

        $totalDepositsThisMonth = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('balance_received') ?? 0;

        $totalOrders = Order::where('user_id', $user->id)->count();

        return [
            'user'                   => $user,
            'balance'                => $user->balance ?? 0,
            'totalOrders'            => $totalOrders,
            'totalOrdersThisMonth'   => $totalOrdersThisMonth,
            'totalDepositsThisMonth' => $totalDepositsThisMonth,
        ];
    }
}
