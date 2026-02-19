<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPago;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPago::create(['nombre' => 'Contado','descripcion' => 'Pago Contado']);
        TipoPago::create(['nombre' => 'Credito','descripcion' => 'Pago al Credito']);
    }
}
