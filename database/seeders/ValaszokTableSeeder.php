<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Valasz;
use App\Models\Kerdes;

class ValaszokTableSeeder extends Seeder
{
    public function run()
    {
        $kerdes1 = Kerdes::first(); // Az első kérdés lekérdezése

        Valasz::create([
            'kerdes_id' => $kerdes1->id,
            'valasz' => 'A háromszög bármelyik oldalának négyzetösszege egyenlő a harmadik oldal négyzetével.',
            'helyes' => true,
        ]);

        Valasz::create([
            'kerdes_id' => $kerdes1->id,
            'valasz' => 'A háromszög területe egyenlő az oldal hosszaival.',
            'helyes' => false,
        ]);
    }
};
