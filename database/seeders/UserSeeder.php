<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed only the system administrator account.
     * Company and Student users must self-register via the registration form.
     */
    public function run(): void
    {
        // System Administrator — the only pre-configured account in the system
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('1234567890'),
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );
    }
}
