<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID Produk')->sortable(),
                TextColumn::make('name')->label('Nama Produk')->searchable(),
                TextColumn::make('price')->label('Harga')->money('IDR')->sortable(),
                TextColumn::make('duration')->label('Durasi'),
                TextColumn::make('warranty')->label('Garansi'),
                TextColumn::make('stock')->label('Sisa Stok')->sortable(),
                ToggleColumn::make('is_active')->label('Status Aktif'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
