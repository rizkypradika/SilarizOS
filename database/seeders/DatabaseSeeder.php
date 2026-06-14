<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, run role and permission seeder
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'adminsilariz@gmail.com'],
            ['name' => 'Owner', 'password' => bcrypt('admin123'), 'role' => 'owner']
        );

        $customer = User::firstOrCreate(
            ['email' => 'customersilariz@gmail.com'],
            ['name' => 'Customer', 'password' => bcrypt('cust123'), 'role' => 'customer']
        );

        // Assign Spatie roles
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        if (! $customer->hasRole('customer')) {
            $customer->assignRole('customer');
        }
    }
}
