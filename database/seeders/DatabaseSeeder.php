<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            CargoSeeder::class,
            PersonalSeeder::class,
            GrupoSeeder::class,
            UserSeeder::class,
            MiEmpresaSeeder::class,
            TiendaSeeder::class,
            FormaPagoSeeder::class,
            TipoPagoSeeder::class,
            MotivoAjusteSeeder::class,
            CXCobrarSeeder::class,
            //CategoriaSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
            ArticuloServicioSeeder::class,
            TiendaArticuloSeeder::class,
            MotivoGastoSeeder::class,
            GastoSeeder::class,
            ProveedorSeeder::class,
            AnimalSeeder::class,
        ]);
    }
}
