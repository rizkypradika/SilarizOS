<?php

namespace App\Filament\Customer\Resources\Products;

use App\Filament\Customer\Resources\Products\Pages\CreateProduct;
use App\Filament\Customer\Resources\Products\Pages\EditProduct;
use App\Filament\Customer\Resources\Products\Pages\ListProducts;
use App\Filament\Customer\Resources\Products\Schemas\ProductForm;
use App\Filament\Customer\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /** Bypass Shield policy — read-only for customer, access controlled by canAccessPanel() */
    public static function canViewAny(): bool  { return true; }
    public static function canCreate(): bool   { return false; }
    public static function canEdit($record): bool   { return false; }
    public static function canDelete($record): bool { return false; }
    public static function getModelLabel(): string
    {
        return 'Stok';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cek Stok';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
