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
            CajeroSeeder::class,
            UserSeeder::class,
            MiEmpresaSeeder::class,
            TiendaSeeder::class,
            FormaPagoSeeder::class,
            TipoPagoSeeder::class,
            MotivoAjusteSeeder::class,
            CategoriaSeeder::class,
            MarcaSeeder::class,
            UnidadMedidaSeeder::class,
            ProveedorSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
            TiendaArticuloSeeder::class,
            MotivoGastoSeeder::class,
            GastoSeeder::class,
            AnimalSeeder::class,
        ]);
    }
}
