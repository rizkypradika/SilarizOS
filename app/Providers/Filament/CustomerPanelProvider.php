<?php

namespace App\Providers\Filament;

use Filament\TeamChat\FilamentTeamChatPlugin;

use App\Filament\Customer\Pages\CustomerDashboard;
use App\Filament\Customer\Pages\Settings;
use App\Filament\Customer\Resources\Deposits\DepositResource;
use App\Filament\Customer\Resources\Orders\OrderResource;
use App\Filament\Customer\Resources\Products\ProductResource;
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
            ->viteTheme('resources/css/filament/customer/theme.css')
            ->brandLogo('')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::USER_MENU_BEFORE,
                fn (): string => \Illuminate\Support\Facades\Blade::render('@if(auth()->check()) <div style="padding: 0.4rem 0.8rem; margin-right: 0.5rem; border-radius: 9999px; background: #c1121f; border: 1px solid #c1121f; display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #ffffff;"><span>💰 Rp {{ number_format(auth()->user()->balance ?? 0, 0, ",", ".") }}</span></div> @endif')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): string => \Illuminate\Support\Facades\Blade::render('@include("custom-logo")')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . asset('css/silariz-theme.css') . '">'
            )
            ->plugin(FilamentTeamChatPlugin::make())
            ->sidebarCollapsibleOnDesktop()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->url(fn (): string => CustomerDashboard::getUrl())
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.pages.customer-dashboard')),
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
                            NavigationItem::make('Pesanan Saya')
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
                                ->url(fn (): string => route('filament.customer.resources.catalog-products.index'))
                                ->icon('heroicon-o-shopping-bag')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.resources.catalog-products.index')),
                        ]),
                    NavigationGroup::make('Bantuan')
                        ->items([
                            NavigationItem::make('Chat Bantuan')
                                ->url(fn (): string => route('filament.customer.pages.team-chat'))
                                ->icon('heroicon-o-chat-bubble-left-right')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.customer.pages.team-chat')),
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
                CustomerDashboard::class,
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
