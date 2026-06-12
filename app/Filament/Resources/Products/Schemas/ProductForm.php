<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable(),
                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),
                TextInput::make('duration')
                    ->label('Durasi (Misal: 1 Bulan)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('warranty')
                    ->label('Garansi (Misal: 1 Bulan)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->required()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}
