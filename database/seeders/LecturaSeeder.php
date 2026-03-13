<?php

namespace Database\Seeders;

use App\Models\Lectura;
use Illuminate\Database\Seeder;

class LecturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lectura::factory(30)->create();
    }
}
