<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Animal::create([
            'nombre' => 'Canino',
            'descripcion' => '',
            'estado' => 1
        ]
      );
      Animal::create(
        [
            'nombre' => 'Felino',
            'descripcion' => '',
            'estado' => 1
        ]
      );
    }
}
