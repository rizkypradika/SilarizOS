<?php

namespace App\Filament\Customer\Resources\Orders\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('ID Pemesanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items_summary')
                    ->label('Produk Dipesan')
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('items.product', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                TextColumn::make('items_count')
                    ->label('Jml Item')
                    ->counts('items')
                    ->alignCenter(),
                TextColumn::make('total_price')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'qris'    => 'QRIS',
                        'ewallet' => 'E-Wallet',
                        'deposit' => 'Deposit',
                        default   => $state ?? '-',
                    })
                    ->color(fn ($state) => match($state) {
                        'qris'    => 'info',
                        'ewallet' => 'success',
                        'deposit' => 'warning',
                        default   => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'   => 'Menunggu',
                        'completed' => 'Berhasil',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'pending'   => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('lihat_akun')
                    ->label('Lihat Disini')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->button()
                    ->visible(fn ($record) => $record->status === 'completed' && !empty($record->account_info))
                    ->modalHeading('Informasi Akun & Akses')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->infolist([
                        \Filament\Infolists\Components\RepeatableEntry::make('account_info')
                            ->hiddenLabel()
                            ->visible(fn ($record) => is_array($record->account_info) && array_is_list($record->account_info))
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('product_name')
                                    ->label('Produk')
                                    ->weight('bold')
                                    ->color('primary'),

                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('email')
                                            ->label('Email')
                                            ->copyable()
                                            ->visible(fn ($state) => !empty($state)),
                                        \Filament\Infolists\Components\TextEntry::make('password')
                                            ->label('Password')
                                            ->copyable()
                                            ->visible(fn ($state) => !empty($state)),
                                        \Filament\Infolists\Components\TextEntry::make('profile')
                                            ->label('Nama Profil')
                                            ->copyable()
                                            ->visible(fn ($state) => !empty($state)),
                                        \Filament\Infolists\Components\TextEntry::make('pin')
                                            ->label('PIN')
                                            ->copyable()
                                            ->visible(fn ($state) => !empty($state)),
                                    ]),

                                \Filament\Infolists\Components\TextEntry::make('invite_link')
                                    ->label('Link Undangan')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab()
                                    ->copyable()
                                    ->color('primary')
                                    ->visible(fn ($state) => !empty($state)),

                                \Filament\Infolists\Components\TextEntry::make('custom_text')
                                    ->label('Detail Informasi')
                                    ->html()
                                    ->visible(fn ($state) => !empty($state)),

                                \Filament\Infolists\Components\TextEntry::make('rules')
                                    ->label('Rules & Guidelines')
                                    ->html()
                                    ->visible(fn ($state) => !empty($state)),
                            ]),

                        // Legacy format support for single object
                        \Filament\Schemas\Components\Section::make('Detail Profil')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('account_info.email')->label('Email')->copyable(),
                                \Filament\Infolists\Components\TextEntry::make('account_info.password')->label('Password')->copyable(),
                                \Filament\Infolists\Components\TextEntry::make('account_info.profile')->label('Nama Profil')->copyable(),
                                \Filament\Infolists\Components\TextEntry::make('account_info.pin')->label('PIN')->copyable(),
                            ])
                            ->columns(2)
                            ->visible(fn ($record) => is_array($record->account_info) && !array_is_list($record->account_info) && ($record->account_info['type'] ?? '') === 'netflix'),

                        \Filament\Schemas\Components\Section::make('Akses Link Undangan')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('account_info.invite_link')->label('Link Undangan')->url(fn ($state) => $state)->openUrlInNewTab()->copyable()->color('primary'),
                            ])
                            ->visible(fn ($record) => is_array($record->account_info) && !array_is_list($record->account_info) && ($record->account_info['type'] ?? '') === 'link'),

                        \Filament\Schemas\Components\Section::make('Detail Informasi')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('account_info.custom_text')->label('')->html(),
                            ])
                            ->visible(fn ($record) => is_array($record->account_info) && !array_is_list($record->account_info) && ($record->account_info['type'] ?? '') === 'custom'),
                    ]),
            ])
            ->toolbarActions([
                // Read only
            ])
            ->defaultSort('created_at', 'desc');
    }
}
