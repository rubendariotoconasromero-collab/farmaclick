<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::updateOrCreate(
            ['nombre' => 'S/N'],
            [
                'matricula' => '0',
                'telefono' => '0',
                'direccion' => 'SD',
                'descripcion' => 'SD',
                'descuento' => 1,
                'estado' => 1
            ]
        );
    }
}
