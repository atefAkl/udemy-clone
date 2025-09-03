<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            // Comment out the old CategorySeeder if you want to use Udemy categories instead
            // CategorySeeder::class,
            UdemyCategorySeeder::class,
        ]);
    }
}
