<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kerdes;
use App\Models\Targy;

class KerdesekTableSeeder extends Seeder
{
    public function run()
    {
        $targyId = Targy::first()->id; 

        Kerdes::create([
            'kerdes' => 'Mi a Pythagoras-tétel?',
            'targy' => $targyId,
        ]);

        Kerdes::create([
            'kerdes' => 'Mi az energia megmaradásának törvénye?',
            'targy' => $targyId,
        ]);
    }
};
