<?php

namespace App\Filament\Customer\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID Produk')->sortable(),
                TextColumn::make('name')->label('Nama Produk')->searchable(),
                TextColumn::make('price')->label('Harga')->money('IDR'),
                TextColumn::make('duration')->label('Durasi'),
                TextColumn::make('warranty')->label('Garansi'),
                TextColumn::make('stock')->label('Sisa Stok'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                // Read-only
            ]);
    }
}
