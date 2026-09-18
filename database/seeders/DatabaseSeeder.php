<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Only the Admin account is seeded. Companies and Students register themselves.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
