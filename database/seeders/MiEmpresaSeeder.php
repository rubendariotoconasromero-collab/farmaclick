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
        MiEmpresa::create(['nombre' => 'Mi Empresa',
        'nit' => '12345678',
        'representante' => 'SIT',
        'direccion' => 'Calle Bolívar',
        'telefono' => '0',
        'descripcion' => 'SD',
        'Localidad' => 'MONTERO',
        'foto' => 'logo.png',
        'correo' => 'sitnorte22@gmail.com',
        'sitio_web' => 'sit-norte.com/',
        'logo_login' => 'logo_login.png',
        'logo_sistema' => 'logo_sistema.png',
        'logo_usuario' => 'logo_usuario.png',
        'fondo_login' => 'fondo_login.jpeg',
        'color_login' => '#7ad6e6',
        'color_menu' => '#000000',]);
    }
}
