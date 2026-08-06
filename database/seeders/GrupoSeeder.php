<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grupo::create([
            'nombre' => 'Administrador',
            'slug' => 'administrador',
            'descripcion' => 'Control General',
            'estado' => 1,
            'is_super_admin' => true,
        ]);
    }
}
