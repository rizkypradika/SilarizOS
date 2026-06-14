<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Permissions that the 'customer' role gets on the admin panel.
     * The customer panel itself doesn't use Shield, so these are purely
     * informational / for any shared resource guards.
     */
    protected array $customerPermissions = [
        // Pesanan
        'ViewAny:Order', 'View:Order', 'Create:Order',
        // Deposit
        'ViewAny:Deposit', 'View:Deposit', 'Create:Deposit',
        // Produk (view only)
        'ViewAny:Product', 'View:Product',
        // Support Ticket
        'ViewAny:SupportTicket', 'View:SupportTicket', 'Create:SupportTicket',
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Normalize any old 'Customer' (capitalized) role to lowercase 'customer'
        Role::where('name', 'Customer')->where('guard_name', 'web')->update(['name' => 'customer']);

        // Create roles if they don't exist
        $adminRole    = Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // Reset cache after creating roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Assign permissions to 'admin' role (all permissions) ---
        $allPermissions = Permission::where('guard_name', 'web')->pluck('name');
        $adminRole->syncPermissions($allPermissions);

        // --- Assign permissions to 'customer' role (limited set) ---
        $customerPerms = Permission::where('guard_name', 'web')
            ->whereIn('name', $this->customerPermissions)
            ->get();
        $customerRole->syncPermissions($customerPerms);

        // --- Assign Spatie roles to users ---

        // Assign 'admin' role to owner/admin users
        \App\Models\User::whereIn('role', ['owner', 'admin'])
            ->each(function ($user) {
                if (! $user->hasRole('admin')) {
                    $user->assignRole('admin');
                }
            });

        // Assign 'customer' role to customer users
        \App\Models\User::where('role', 'customer')
            ->each(function ($user) {
                if (! $user->hasRole('customer')) {
                    $user->assignRole('customer');
                }
            });
    }
}
