<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Genero::create([
            'user_id' => 1,
            'nombre' => 'Terror'
        ]);

        \App\Models\Genero::create([
            'user_id' => 1,
            'nombre' => 'Romance'
        ]);

        \App\Models\Genero::create([
            'user_id' => 1,
            'nombre' => 'Aventuras'
        ]);
    }
}
