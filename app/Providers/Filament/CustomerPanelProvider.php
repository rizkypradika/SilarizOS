<?php

namespace App\Providers\Filament;

use App\Filament\Customer\Pages\Settings;
use App\Filament\Customer\Resources\Deposits\DepositResource;
use App\Filament\Customer\Resources\Orders\OrderResource;
use App\Filament\Customer\Resources\Products\ProductResource;
use App\Filament\Customer\Resources\SupportTickets\SupportTicketResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->url(fn (): string => Dashboard::getUrl(panel: 'customer'))
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.pages.dashboard')),
                        ]),
                    NavigationGroup::make('Pesanan')
                        ->items([
                            NavigationItem::make('Buat Pesanan')
                                ->url(fn (): string => OrderResource::getUrl('create'))
                                ->icon('heroicon-o-plus-circle')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.orders.create')),
                            NavigationItem::make('Cek Stok')
                                ->url(fn (): string => ProductResource::getUrl('index'))
                                ->icon('heroicon-o-archive-box')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.products.index')),
                            NavigationItem::make('Daftar Pesanan Anda')
                                ->url(fn (): string => OrderResource::getUrl('index'))
                                ->icon('heroicon-o-clipboard-document-list')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.orders.index')),
                        ]),
                    NavigationGroup::make('Deposit')
                        ->items([
                            NavigationItem::make('Buat Deposit')
                                ->url(fn (): string => DepositResource::getUrl('create'))
                                ->icon('heroicon-o-banknotes')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.deposits.create')),
                            NavigationItem::make('Riwayat')
                                ->url(fn (): string => DepositResource::getUrl('index'))
                                ->icon('heroicon-o-clock')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.deposits.index')),
                            NavigationItem::make('Laporan')
                                ->url(fn (): string => \App\Filament\Customer\Pages\DepositReport::getUrl())
                                ->icon('heroicon-o-document-chart-bar')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.pages.deposit-report')),
                        ]),
                    NavigationGroup::make('Layanan')
                        ->items([
                            NavigationItem::make('Daftar Produk')
                                ->url(url('/admin/products'))
                                ->icon('heroicon-o-shopping-bag')
                                ->visible(fn (): bool => auth()->user()?->role === 'owner'),
                        ]),
                    NavigationGroup::make('Bantuan')
                        ->items([
                            NavigationItem::make('Buat Bantuan')
                                ->url(fn (): string => SupportTicketResource::getUrl('create'))
                                ->icon('heroicon-o-lifebuoy')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.support-tickets.create')),
                            NavigationItem::make('Riwayat')
                                ->url(fn (): string => SupportTicketResource::getUrl('index'))
                                ->icon('heroicon-o-ticket')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.support-tickets.index')),
                        ]),
                    NavigationGroup::make('Pengaturan')
                        ->items([
                            NavigationItem::make('Pengaturan Akun')
                                ->url(fn (): string => Settings::getUrl())
                                ->icon('heroicon-o-cog-6-tooth')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.pages.settings')),
                        ]),
                ]);
            })
            ->discoverResources(in: app_path('Filament/Customer/Resources'), for: 'App\Filament\Customer\Resources')
            ->discoverPages(in: app_path('Filament/Customer/Pages'), for: 'App\Filament\Customer\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\Filament\Customer\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
