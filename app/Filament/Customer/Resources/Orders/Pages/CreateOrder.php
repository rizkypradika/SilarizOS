<?php

namespace App\Filament\Customer\Resources\Orders\Pages;

use App\Filament\Customer\Resources\Orders\OrderResource;
use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\HtmlString;

class CreateOrder extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static ?string $title = 'Buat Pesanan';

    protected static string $resource = OrderResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Order Produk')
                ->schema([
                    Hidden::make('user_id')
                        ->default(fn () => auth()->id()),
                    TextInput::make('user_name')
                        ->label('Nama Lengkap')
                        ->default(fn () => auth()->user()?->name)
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('product_id')
                        ->label('Pilih Produk')
                        ->options(Product::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($set, ?string $state) {
                            if (! $state) {
                                $set('total_price', null);
                                $set('duration', null);
                                $set('warranty', null);
                                return;
                            }
                            $product = Product::find($state);
                            if ($product) {
                                $set('total_price', $product->price);
                                $set('duration', $product->duration);
                                $set('warranty', $product->warranty);
                            }
                        }),
                    TextInput::make('total_price')
                        ->label('Harga')
                        ->numeric()
                        ->readOnly()
                        ->required()
                        ->prefix('Rp'),
                    TextInput::make('duration')
                        ->label('Durasi')
                        ->readOnly(),
                    TextInput::make('warranty')
                        ->label('Garansi')
                        ->readOnly(),
                ]),
            Step::make('Pembayaran')
                ->schema([
                    Radio::make('payment_method')
                        ->label('Pilih Pembayaran')
                        ->options([
                            'qris' => 'QRIS',
                            'ewallet' => 'E-Wallet',
                        ])
                        ->required()
                        ->live(),
                    Placeholder::make('qris_image')
                        ->label('')
                        ->content(new HtmlString('<div style="text-align: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" style="max-width: 200px; border-radius: 8px; margin: 0 auto;" /><p class="mt-2 text-sm text-gray-500">Scan QR Code di atas untuk membayar</p></div>'))
                        ->visible(fn ($get) => $get('payment_method') === 'qris'),
                    Placeholder::make('ewallet_info')
                        ->label('')
                        ->content(new HtmlString('<div style="text-align: center; padding: 1rem; background-color: #f3f4f6; border-radius: 8px;"><strong>Transfer ke GOPAY, DANA, Shopeepay, LinkAja, OVO:</strong><br><span style="font-size: 1.25rem; color: #10b981; font-weight: bold;">081332964581</span></div>'))
                        ->visible(fn ($get) => $get('payment_method') === 'ewallet'),
                ]),
        ];
    }
}
