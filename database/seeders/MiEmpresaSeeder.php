<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MiEmpresa;

class MiEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MiEmpresa::create([
        'nombre' => null,
        'nit' => null,
        'representante' => null,
        'direccion' => null,
        'telefono' => null,
        'descripcion' => null,
        'Localidad' => null,
        'foto' => null,
        'correo' => null,
        'sitio_web' => null,
        'logo_login' => null,
        'logo_sistema' => null,
        'logo_usuario' => null,
        'fondo_login' => null,
        'color_login' => '#7ad6e6',
        'color_menu' => '#000000',]);
    }
}
