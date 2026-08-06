<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiendaArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Vincula cada artículo del catálogo a la tienda con stock en 0:
     * el stock físico es operativo y se genera con las compras del negocio,
     * no se siembra desde el catálogo.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('INSERT INTO tienda_articulo (id_articulo, id_tienda, stock) SELECT id, 1, 0 FROM articulo');
    }
}
