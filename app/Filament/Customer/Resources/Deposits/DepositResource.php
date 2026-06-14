<?php

namespace App\Filament\Customer\Resources\Deposits;

use App\Filament\Customer\Resources\Deposits\Pages\CreateDeposit;
use App\Filament\Customer\Resources\Deposits\Pages\EditDeposit;
use App\Filament\Customer\Resources\Deposits\Pages\ListDeposits;
use App\Filament\Customer\Resources\Deposits\Schemas\DepositForm;
use App\Filament\Customer\Resources\Deposits\Tables\DepositsTable;
use App\Models\Deposit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /** Bypass Shield policy — access controlled by canAccessPanel() */
    public static function canViewAny(): bool  { return true; }
    public static function canCreate(): bool   { return true; }
    public static function canEdit($record): bool   { return true; }
    public static function canDelete($record): bool { return false; }
    public static function getModelLabel(): string
    {
        return 'Deposit';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Riwayat Deposit';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return DepositForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepositsTable::configure($table);
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
            'index' => ListDeposits::route('/'),
            'create' => CreateDeposit::route('/create'),
            'edit' => EditDeposit::route('/{record}/edit'),
        ];
    }
}
