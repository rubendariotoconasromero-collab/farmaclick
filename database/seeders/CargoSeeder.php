<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cargo::create(['nombre' => 'Administrador','descripcion' => 'Administrador','estado' => 1,]);
        Cargo::create(['nombre' => 'Doctor(a)','descripcion' => 'Doctor(a)','estado' => 1,]);
        //\App\Models\Cargo::factory(12)->create();
    }
}
