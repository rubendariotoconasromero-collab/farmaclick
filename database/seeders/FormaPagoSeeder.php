<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormaPago;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormaPago::create(['nombre' => 'Cuenta por Cobrar','descripcion' => 'Pago a Crédito']);
        FormaPago::create(['nombre' => 'Efectivo','descripcion' => 'Pago Efectivo']);
        FormaPago::create(['nombre' => 'Transferencia','descripcion' => 'Pago desde una Cuenta Bancaria']);
        FormaPago::create(['nombre' => 'Pago por QR','descripcion' => 'Pago por el código QR']);
        FormaPago::create(['nombre' => 'Depósito','descripcion' => 'Pago por Depósito']);
    }
}
