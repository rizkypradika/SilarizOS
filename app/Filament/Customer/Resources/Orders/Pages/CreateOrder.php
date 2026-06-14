<?php

namespace App\Filament\Customer\Resources\Orders\Pages;

use App\Filament\Customer\Resources\Orders\OrderResource;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
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

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Buat Pesanan Anda',
        ];
    }

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

                    // ── Multi-product Repeater ──────────────────────────────
                    Repeater::make('orderItems')
                        ->label('Daftar Produk Pesanan')
                        ->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->options(Product::where('is_active', true)->pluck('name', 'id'))
                                ->required()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->live()
                                ->afterStateUpdated(function ($set, $get, ?string $state) {
                                    if (! $state) {
                                        $set('unit_price', null);
                                        $set('duration', null);
                                        $set('warranty', null);
                                        $set('subtotal', null);
                                        return;
                                    }
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('unit_price', $product->price);
                                        $set('duration',   $product->duration);
                                        $set('warranty',   $product->warranty);
                                        $set('max_stock',  $product->stock);
                                        $qty = (int) ($get('quantity') ?? 1);
                                        $set('subtotal', $product->price * $qty);
                                    }
                                })
                                ->columnSpan(2),

                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required()
                                ->live(onBlur: false)
                                ->afterStateUpdated(function ($set, $get, ?string $state) {
                                    $price    = (float) ($get('unit_price') ?? 0);
                                    $qty      = max(1, (int) ($state ?? 1));
                                    $maxStock = (int) ($get('max_stock') ?? PHP_INT_MAX);

                                    $set('subtotal', $price * $qty);

                                    // Tampilkan alert jika melebihi stok
                                    if ($maxStock > 0 && $qty > $maxStock) {
                                        $set('stock_warning', "⚠️ Stok hanya tersedia {$maxStock} unit!");
                                    } else {
                                        $set('stock_warning', null);
                                    }
                                })
                                ->rules([
                                    fn ($get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $maxStock = (int) ($get('max_stock') ?? PHP_INT_MAX);
                                        if ($maxStock > 0 && (int) $value > $maxStock) {
                                            $fail("Jumlah melebihi stok tersedia ({$maxStock} unit).");
                                        }
                                    },
                                ])
                                ->helperText(function ($get): ?HtmlString {
                                    $warning = $get('stock_warning');
                                    $stock   = (int) ($get('max_stock') ?? 0);
                                    if ($warning) {
                                        return new HtmlString('<span style="color:#dc2626; font-weight:500; font-size:0.85rem;">' . $warning . '</span>');
                                    }
                                    if ($stock > 0) {
                                        return new HtmlString('<span style="color:#16a34a; font-weight:500; font-size:0.85rem;">✅ Stok tersedia: ' . $stock . '</span>');
                                    }
                                    return null;
                                })
                                ->columnSpan(1),

                            // Hidden: simpan max_stock untuk validasi
                            Hidden::make('max_stock')->default(0),
                            Hidden::make('stock_warning')->default(null),

                            TextInput::make('unit_price')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->readOnly()
                                ->prefix('Rp')
                                ->columnSpan(1),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->readOnly()
                                ->prefix('Rp')
                                ->columnSpan(1),

                            TextInput::make('duration')
                                ->label('Durasi')
                                ->readOnly()
                                ->columnSpan(1),

                            TextInput::make('warranty')
                                ->label('Garansi')
                                ->readOnly()
                                ->columnSpan(1),
                        ])
                        ->columns(7)
                        ->minItems(1)
                        ->addActionLabel('+ Tambah Produk')
                        ->reorderable(false)
                        ->live()
                        ->afterStateUpdated(function ($set, $get) {
                            $total = collect($get('orderItems') ?? [])
                                ->sum(fn ($item) => (float) ($item['subtotal'] ?? 0));
                            $set('total_price', $total);
                        })
                        ->columnSpanFull(),

                    // ── Grand Total ─────────────────────────────────────────
                    Placeholder::make('grand_total_display')
                        ->label('')
                        ->content(function ($get): HtmlString {
                            $total = collect($get('orderItems') ?? [])
                                ->sum(fn ($item) => (float) ($item['subtotal'] ?? 0));
                            $formatted = 'Rp ' . number_format($total, 0, ',', '.');
                            return new HtmlString(
                                '<div style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:space-between;
                                    padding: 1rem 1.25rem;
                                    background: linear-gradient(135deg, rgba(193, 18, 31, 0.15), rgba(193, 18, 31, 0.05));
                                    border: 2px solid #c1121f;
                                    border-radius: 10px;
                                    margin-top: 0.5rem;
                                ">
                                    <span style="font-size:1rem; font-weight:600; color:#780000;">💰 Total Pembayaran</span>
                                    <span style="font-size:1.5rem; font-weight:700; color:#c1121f;">' . $formatted . '</span>
                                </div>'
                            );
                        })
                        ->live()
                        ->columnSpanFull(),

                    // Hidden field to carry total_price to next step
                    Hidden::make('total_price')
                        ->default(0),
                ]),

            Step::make('Pembayaran')
                ->schema([
                    // Show order summary
                    Placeholder::make('order_summary')
                        ->label('Ringkasan Pesanan')
                        ->content(function ($get): HtmlString {
                            $items  = collect($get('orderItems') ?? []);
                            $total  = $items->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
                            $rows   = '';
                            foreach ($items as $item) {
                                $prod  = Product::find($item['product_id'] ?? null);
                                $name  = $prod?->name ?? '-';
                                $qty   = $item['quantity'] ?? 1;
                                $sub   = 'Rp ' . number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.');
                                $rows .= "<tr>
                                    <td style='padding:4px 8px;'>{$name}</td>
                                    <td style='padding:4px 8px; text-align:center;'>{$qty}</td>
                                    <td style='padding:4px 8px; text-align:right;'>{$sub}</td>
                                </tr>";
                            }
                            $totalFmt = 'Rp ' . number_format($total, 0, ',', '.');
                            return new HtmlString("
                                <div style='border-radius:8px; overflow:hidden; border:1px solid #e5e7eb;'>
                                    <table style='width:100%; border-collapse:collapse; font-size:0.9rem;'>
                                        <thead>
                                            <tr style='background:#f3f4f6;'>
                                                <th style='padding:8px; text-align:left;'>Produk</th>
                                                <th style='padding:8px; text-align:center;'>Qty</th>
                                                <th style='padding:8px; text-align:right;'>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>{$rows}</tbody>
                                        <tfoot>
                                            <tr style='background:#fef3c7; border-top:2px solid #f59e0b;'>
                                                <td colspan='2' style='padding:8px; font-weight:700;'>Total Pembayaran</td>
                                                <td style='padding:8px; text-align:right; font-weight:700; font-size:1.1rem; color:#b45309;'>{$totalFmt}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            ");
                        })
                        ->columnSpanFull(),

                    Radio::make('payment_method')
                        ->label('Pilih Pembayaran')
                        ->options([
                            'deposit' => '💳 Saldo Deposit',
                            'qris'    => 'QRIS',
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

                    Placeholder::make('deposit_info')
                        ->label('')
                        ->content(function (): HtmlString {
                            $balance = auth()->user()?->balance ?? 0;
                            $fmt     = 'Rp ' . number_format((float) $balance, 0, ',', '.');
                            return new HtmlString(
                                '<div style="padding:1rem; background:#ecfdf5; border:1px solid #10b981; border-radius:8px; text-align:center;">
                                    <p style="margin:0; color:#065f46;">Saldo Deposit Anda</p>
                                    <p style="margin:4px 0 0; font-size:1.5rem; font-weight:700; color:#059669;">' . $fmt . '</p>
                                </div>'
                            );
                        })
                        ->visible(fn ($get) => $get('payment_method') === 'deposit'),
                ]),

            Step::make('Validasi Pembayaran')
                ->schema([
                    Placeholder::make('validation_simulation')
                        ->hiddenLabel()
                        ->content(function ($get): HtmlString {
                            $payment = $get('payment_method');
                            $items = collect($get('orderItems') ?? []);
                            $total = $items->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
                            $balance = auth()->user()?->balance ?? 0;
                            
                            $isValid = true;
                            $msgError = '';
                            
                            if ($payment === 'deposit') {
                                if ($balance < $total) {
                                    $isValid = false;
                                    $msgError = 'Saldo deposit tidak mencukupi (Kurang Rp ' . number_format($total - $balance, 0, ',', '.') . ').';
                                }
                            }
                            
                            $jsIsValid = $isValid ? 'true' : 'false';

                            return new HtmlString('
                                <style>
                                @keyframes silariz-spin {
                                    from { transform: rotate(0deg); }
                                    to { transform: rotate(360deg); }
                                }
                                .silariz-spinner {
                                    animation: silariz-spin 1s linear infinite;
                                    color: #f59e0b;
                                }
                                </style>
                                <div x-data="{ timeLeft: 30, done: false, styleEl: null }" 
                                     x-init="
                                        styleEl = document.createElement(\'style\');
                                        styleEl.innerHTML = \'button[type=submit] { display: none !important; }\';
                                        document.head.appendChild(styleEl);
                                        
                                        let interval = setInterval(() => { 
                                            if(timeLeft > 0) {
                                                timeLeft--; 
                                            } else {
                                                done = true; 
                                                clearInterval(interval);
                                                
                                                if(\''.$jsIsValid.'\' === \'true\') {
                                                    if (styleEl && styleEl.parentNode) {
                                                        styleEl.parentNode.removeChild(styleEl);
                                                    }
                                                }
                                            }
                                        }, 1000);

                                        // Cleanup ketika komponen dihancurkan (misal klik back)
                                        return () => {
                                            clearInterval(interval);
                                            if (styleEl && styleEl.parentNode) {
                                                styleEl.parentNode.removeChild(styleEl);
                                            }
                                        };
                                     "
                                     class="text-center p-6 bg-white rounded-lg border shadow-sm">
                                    
                                    <div x-show="!done" class="flex flex-col items-center justify-center space-y-4">
                                        <svg style="width: 4rem; height: 4rem;" class="silariz-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"></circle>
                                            <path fill="currentColor" fill-opacity="0.75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium" style="color: #111827;">Sedang memvalidasi pembayaran...</h3>
                                        <p class="text-3xl font-bold" style="color: #d97706;" x-text="timeLeft + \' detik\'"></p>
                                    </div>
                                    
                                    <div x-show="done" style="display: none;">
                                        '.($isValid 
                                            ? '<div class="text-green-600 flex flex-col items-center gap-2">
                                                 <svg style="width: 4rem; height: 4rem; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                 <h3 class="text-xl font-bold" style="color: #16a34a;">Validasi Berhasil! Pesanan Berhasil.</h3>
                                                 <p style="color: #4b5563;">Silakan klik tombol Lanjutkan di bawah untuk menyimpan dan melihat riwayat pesanan.</p>
                                               </div>'
                                            : '<div class="text-red-600 flex flex-col items-center gap-2">
                                                 <svg style="width: 4rem; height: 4rem; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                 <h3 class="text-xl font-bold" style="color: #dc2626;">Validasi Gagal</h3>
                                                 <p style="color: #dc2626;">'.$msgError.'</p>
                                                 <p class="text-sm mt-2" style="color: #6b7280;">Silakan klik tombol <b>Previous</b> (Kembali) untuk mengganti metode pembayaran.</p>
                                               </div>').'
                                    </div>
                                </div>
                            ');
                        }),
                ]),
        ];
    }

    protected function getSubmitFormAction(): \Filament\Actions\Action
    {
        return parent::getSubmitFormAction()
            ->label('Lanjutkan');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Unset orderItems so Filament doesn't try to insert it into the 'orders' table
        unset($data['orderItems']);

        return $data;
    }

    /**
     * After the Order header is saved, persist each item into order_items
     * and update the order's total_price.
     */
    protected function afterCreate(): void
    {
        $order = $this->record;
        $items = $this->data['orderItems'] ?? [];

        $total = 0;

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $quantity  = max(1, (int) ($item['quantity'] ?? 1));
            $product   = Product::find($productId);

            if (! $product) continue;

            $unitPrice = (float) $product->price;
            $subtotal  = $unitPrice * $quantity;
            $total    += $subtotal;

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'unit_price' => $unitPrice,
                'subtotal'   => $subtotal,
                'duration'   => $product->duration,
                'warranty'   => $product->warranty,
            ]);

            // Deduct product stock
            if ($product->stock > 0) {
                $product->decrement('stock', $quantity);
            }
        }

        // Update the order header total and set first product for backward compat
        $firstItem = $items[0] ?? null;
        $order->update([
            'total_price' => $total,
            'product_id'  => $firstItem['product_id'] ?? null,
            'quantity'    => count($items),
        ]);

        // Deduct balance if payment method is deposit
        if (($this->data['payment_method'] ?? '') === 'deposit') {
            $user = auth()->user();
            if ($user->balance >= $total) {
                $user->decrement('balance', $total);
            } else {
                // If somehow bypassed frontend validation
                abort(403, 'Saldo deposit tidak mencukupi.');
            }
        }
    }
}
