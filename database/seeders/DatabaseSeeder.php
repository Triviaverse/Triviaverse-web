<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Hívjuk meg az egyes seeder osztályokat
        $this->call([
            UsersTableSeeder::class,
            TargyakTableSeeder::class,
            KerdesekTableSeeder::class,
            ValaszokTableSeeder::class,
            PontokTableSeeder::class,
        ]);
    }
};
