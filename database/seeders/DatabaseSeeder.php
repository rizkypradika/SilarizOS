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
        \App\Models\User::firstOrCreate(
            ['email' => 'adminsilariz@gmail.com'],
            ['name' => 'Owner', 'password' => bcrypt('admin123'), 'role' => 'owner']
        );
        \App\Models\User::firstOrCreate(
            ['email' => 'customersilariz@gmail.com'],
            ['name' => 'Customer', 'password' => bcrypt('cust123'), 'role' => 'customer']
        );
    }
}
