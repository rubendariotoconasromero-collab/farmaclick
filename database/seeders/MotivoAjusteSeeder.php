<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotivoAjuste;

class MotivoAjusteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoAjuste::create(['nombre' => 'INVENTARIO INICIAL','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'INGRESO','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'EGRESO','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'COSTO COMPRA','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'PRECIO VENTA','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'COMPRA PRODUCTO','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'VENTA 1','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'VENTA 2','descripcion' => 'SD']);
        MotivoAjuste::create(['nombre' => 'VENTA 3','descripcion' => 'SD']);
    }
}
