<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;
use App\Models\Permission;

class CajeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grupo = Grupo::updateOrCreate(
            ['slug' => 'cajero'],
            [
                'nombre' => 'Cajero',
                'descripcion' => 'Registro de ventas y manejo de caja',
                'estado' => 1,
                'is_super_admin' => false,
            ]
        );

        $permisos = Permission::whereIn('key', [
            'dashboard.view',
            'sales.create',
            'sales.view',
            'sales.payments',
            'cash.manage',
            'inventory.view',
        ])->pluck('id');

        $grupo->permissions()->sync($permisos);
    }
}
