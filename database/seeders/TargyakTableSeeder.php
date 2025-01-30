<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Targy;

class TargyakTableSeeder extends Seeder
{
    public function run()
    {
        // Például három tárgy létrehozása
        Targy::create([
            'targyneve' => 'Matematika',
        ]);

        Targy::create([
            'targyneve' => 'Fizika',
        ]);

        Targy::create([
            'targyneve' => 'Biológia',
        ]);
    }
};
