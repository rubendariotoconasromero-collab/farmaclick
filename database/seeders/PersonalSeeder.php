<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personal;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Personal::create(['nombre' => 'Administrador Principal','telefono' => '0','direccion' => '0','descripcion' => '0','id_cargo' => 1,]);
        //\App\Models\Personal::factory(100)->create();
    }
}
