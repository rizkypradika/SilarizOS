<?php

namespace App\Filament\Customer\Resources\Products\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->badge()
                    ->color('info'),

                TextColumn::make('warranty')
                    ->label('Garansi')
                    ->badge()
                    ->color('success'),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0  => 'danger',
                        $state <= 5  => 'warning',
                        default      => 'success',
                    }),
            ])
            ->defaultGroup(
                Group::make('brand')
                    ->label('Kategori Produk')
                    ->getKeyFromRecordUsing(fn ($record): string => $record->brand)
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy('name', $direction))
                    ->collapsible()
            )
            ->defaultSort('name')
            ->filters([])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                // Read-only
            ]);
    }
}
