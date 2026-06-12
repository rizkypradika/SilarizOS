<?php

namespace App\Filament\Customer\Resources\Deposits\Pages;

use App\Filament\Customer\Resources\Deposits\DepositResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;
use Closure;

class CreateDeposit extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static ?string $title = 'Buat Deposit';

    protected static string $resource = DepositResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Pilih Pembayaran')
                ->schema([
                    Hidden::make('user_id')
                        ->default(fn () => auth()->id()),
                    Hidden::make('status')
                        ->default('pending'),
                    Radio::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->options([
                            'qris' => 'QRIS',
                            'ewallet' => 'E-Wallet',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($set, $state) {
                            if ($state === 'qris') {
                                $set('provider', null);
                            }
                        }),
                    Select::make('provider')
                        ->label('Pilih E-Wallet')
                        ->options([
                            'gopay' => 'GoPay',
                            'dana' => 'DANA',
                            'linkaja' => 'LinkAja',
                            'ovo' => 'OVO',
                        ])
                        ->visible(fn ($get) => $get('payment_method') === 'ewallet')
                        ->required(fn ($get) => $get('payment_method') === 'ewallet'),
                ]),
            Step::make('Nominal Deposit')
                ->schema([
                    TextInput::make('amount')
                        ->label('Jumlah Deposit')
                        ->numeric()
                        ->integer()
                        ->extraInputAttributes([
                            'onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57',
                        ])
                        ->required()
                        ->prefix('Rp')
                        ->live(onBlur: true)
                        ->helperText(fn ($get) => $get('payment_method') === 'qris' ? 'Minimal Rp 1.000' : 'Minimal Rp 10.000')
                        ->rules([
                            fn ($get) => function (string $attribute, $value, Closure $fail) use ($get) {
                                $amount = (float) str_replace('.', '', (string) $value);
                                $method = $get('payment_method');
                                if ($method === 'qris' && $amount < 1000) {
                                    $fail('Minimal deposit menggunakan QRIS adalah Rp 1.000');
                                }
                                if ($method === 'ewallet' && $amount < 10000) {
                                    $fail('Minimal deposit menggunakan E-Wallet adalah Rp 10.000');
                                }
                            },
                        ])
                        ->afterStateUpdated(function ($set, $get, $state) {
                            $amount = (float) str_replace('.', '', (string) $state);
                            $method = $get('payment_method');
                            $admin_fee = 0;
                            if ($method === 'qris') {
                                $admin_fee = round($amount * 0.001, 2); // 0.10%
                            }
                            $set('amount', $amount); // Auto-correct dot inputs
                            $set('admin_fee', $admin_fee);
                            $set('total_payment', $amount + $admin_fee);
                            $set('balance_received', $amount);
                        }),
                    TextInput::make('admin_fee')
                        ->label('Biaya Admin')
                        ->numeric()
                        ->readOnly()
                        ->prefix('Rp')
                        ->helperText(fn ($get) => $get('payment_method') === 'qris' ? '0.10%' : 'Free'),
                    TextInput::make('balance_received')
                        ->label('Saldo Didapat')
                        ->numeric()
                        ->readOnly()
                        ->prefix('Rp'),
                    TextInput::make('total_payment')
                        ->label('Total Pembayaran')
                        ->numeric()
                        ->readOnly()
                        ->prefix('Rp')
                        ->dehydrated(false),
                ]),
        ];
    }
}
