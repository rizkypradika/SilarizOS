<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('order_number')
                    ->label('ID Pemesanan')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('items_summary')
                    ->label('Produk Dipesan')
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('items.product', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    }),
                \Filament\Tables\Columns\TextColumn::make('items_count')
                    ->label('Jml Item')
                    ->counts('items')
                    ->alignCenter(),
                \Filament\Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('payment_method')
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
                \Filament\Tables\Columns\TextColumn::make('status')
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
                \Filament\Tables\Columns\TextColumn::make('account_info')
                    ->label('Format Info Akun')
                    ->getStateUsing(function ($record) {
                        if (!is_array($record->account_info)) return '-';
                        if (isset($record->account_info['type'])) return ucfirst($record->account_info['type']);
                        return count($record->account_info) > 0 ? count($record->account_info) . ' Akun' : '-';
                    })
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
