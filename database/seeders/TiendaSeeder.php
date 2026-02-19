<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tienda;

class TiendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tienda::create(['cod_tienda' => 'TND000','nombre' => 'Farmacia Suarez','direccion' => 'Villa Verde','estado' => 1, 'id_mi_empresa' => 1,'foto' => 'logo.png',]);

        // Tienda::create(['cod_tienda' => 'TND001','nombre' => 'Tienda Puritana','direccion' => 'Okinawa','cod_almacen' => 'TND000','estado' => 1, 'id_mi_empresa' => 2,]);
        // Tienda::create(['cod_tienda' => 'TND002','nombre' => 'Tienda Inteligente','direccion' => 'Warnes','cod_almacen' => 'TND000','estado' => 1, 'id_mi_empresa' => 3,]);
        // Tienda::create(['cod_tienda' => 'TND003','nombre' => 'Tienda Samsung','direccion' => 'Montero','cod_almacen' => 'TND000','estado' => 1, 'id_mi_empresa' => 4,]);
    }
}
