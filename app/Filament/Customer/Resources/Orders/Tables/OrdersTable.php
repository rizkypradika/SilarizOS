<?php

namespace App\Filament\Customer\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID Pemesanan')->sortable(),
                TextColumn::make('product.name')->label('Nama Produk')->searchable(),
                TextColumn::make('duration')->label('Durasi'),
                TextColumn::make('total_price')->label('Harga')->money('IDR'),
                TextColumn::make('warranty')->label('Garansi'),
                TextColumn::make('account_details')->label('Detail Akun')->wrap(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Read only
            ])
            ->toolbarActions([
                // Read only
            ]);
    }
}
