<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Placeholder::make('product_name')
                    ->label('Produk')
                    ->content(fn ($record) => $record?->itemsSummary ?? '-'),
                \Filament\Forms\Components\Placeholder::make('total_price')
                    ->label('Total Bayar')
                    ->content(fn ($record) => $record ? 'Rp ' . number_format($record->total_price, 0, ',', '.') : '-'),

                \Filament\Forms\Components\Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending'   => 'Menunggu',
                        'completed' => 'Berhasil',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->live()
                    ->required(),

                \Filament\Forms\Components\Repeater::make('account_info')
                    ->label('Informasi Akun & Akses')
                    ->visible(fn ($get) => $get('status') === 'completed')
                    ->itemLabel(fn (array $state): ?string => $state['product_name'] ?? 'Akun Baru')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('product_name')
                            ->label('Nama Produk / Label Akun')
                            ->required()
                            ->columnSpanFull(),

                        \Filament\Forms\Components\Select::make('type')
                            ->label('Format Pengiriman')
                            ->options([
                                'netflix' => 'Detail Profil (Email, Password, Profil, PIN)',
                                'link'    => 'Link Undangan (Canva, YouTube, Spotify)',
                                'custom'  => 'Teks Bebas Kustom',
                            ])
                            ->required()
                            ->live(),

                        \Filament\Schemas\Components\Grid::make(2)
                            ->visible(fn ($get) => $get('type') === 'netflix')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('email')
                                    ->label('Email'),
                                \Filament\Forms\Components\TextInput::make('password')
                                    ->label('Password'),
                                \Filament\Forms\Components\TextInput::make('profile')
                                    ->label('Nama Profil'),
                                \Filament\Forms\Components\TextInput::make('pin')
                                    ->label('PIN Profil'),
                            ]),

                        \Filament\Forms\Components\TextInput::make('invite_link')
                            ->label('Link Undangan')
                            ->url()
                            ->visible(fn ($get) => $get('type') === 'link')
                            ->columnSpanFull(),

                        \Filament\Forms\Components\RichEditor::make('custom_text')
                            ->label('Informasi Kustom')
                            ->visible(fn ($get) => $get('type') === 'custom')
                            ->columnSpanFull(),

                        \Filament\Forms\Components\RichEditor::make('rules')
                            ->label('Rules & Guidelines (Aturan)')
                            ->helperText('Tulis aturan seperti "Dilarang ganti profil", "Garansi hangus jika ganti sandi", dll.')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
