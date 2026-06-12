<?php

namespace App\Filament\Customer\Resources\Deposits\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class DepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Deposit')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(function ($record) {
                        $method = strtoupper($record->payment_method);
                        if ($record->provider) {
                            $method .= ' (' . strtoupper($record->provider) . ')';
                        }
                        return $method;
                    }),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('balance_received')
                    ->label('Saldo Didapat')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Read-only
            ])
            ->bulkActions([
                // Read-only
            ]);
    }
}
