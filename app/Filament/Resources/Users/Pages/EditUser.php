<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $user = $this->record->fresh();

        // Sync the 'role' column based on assigned Spatie roles
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            $user->update(['role' => 'admin']);
        } else {
            // Default to 'customer' if no role is set
            if ($user->roles->isEmpty()) {
                $user->assignRole('customer');
            }
            $user->update(['role' => 'customer']);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
