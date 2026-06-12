<?php

namespace App\Filament\Customer\Resources\Products\Schemas;

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
                    ->disabled(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->disabled(),
                TextInput::make('price')
                    ->label('Harga')
                    ->prefix('Rp')
                    ->disabled(),
                TextInput::make('duration')
                    ->label('Durasi')
                    ->disabled(),
                TextInput::make('warranty')
                    ->label('Garansi')
                    ->disabled(),
                TextInput::make('stock')
                    ->label('Stok')
                    ->disabled(),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->disabled(),
            ]);
    }
}
