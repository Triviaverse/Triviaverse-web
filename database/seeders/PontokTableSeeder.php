<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pont;
use App\Models\Targy;

class PontokTableSeeder extends Seeder
{
    public function run()
    {
        $targyId = Targy::first()->id; // Kiválasztjuk az első tárgyat

        Pont::create([
            'targy' => $targyId,
            'osszpont' => 80,
            'email' => 'user@example.com',
        ]);

        Pont::create([
            'targy' => $targyId,
            'osszpont' => 95,
            'email' => 'student@example.com',
        ]);
    }
};
