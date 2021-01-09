<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LanguageSeeder::class,
            EventSeeder::class,
            TradingCardGameSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
