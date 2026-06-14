<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;

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
