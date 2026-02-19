<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Articulo;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\Articulo::factory(200)->create();
        // Articulo::create(
        //     [
        //         'cod_producto' => 'E-100',
        //         'cod_proveedor' => 'P-100',
        //         'cod_ean' => '1234567',
        //         'nombre' => 'Televisor Samgung',
        //         'costo_compra' => 3500,
        //         'costo_unitario' => 5000,
        //         'costo_mayorista' => 4500,
        //         'costo_preferencial' => 4000,
        //         'stock_minimo' => 30,
        //         'tipo_producto' => 'Producto Venta',
        //         'descripcion' => 'Televisor Samsung 65pulg',
        //         'estado' => 1,
        //         'id_categoria' => 1
        //     ]
        // );
        // Articulo::create(
        //     [
        //         'cod_producto' => 'E-100',
        //         'cod_proveedor' => 'P-100',
        //         'cod_ean' => '1234567',
        //         'nombre' => 'Televisor Lg',
        //         'costo_compra' => 3500,
        //         'costo_unitario' => 5000,
        //         'costo_mayorista' => 4500,
        //         'costo_preferencial' => 4000,
        //         'stock_minimo' => 30,
        //         'tipo_producto' => 'Producto Venta',
        //         'descripcion' => 'Televisor Lg 65pulg',
        //         'estado' => 1,
        //         'id_categoria' => 1
        //     ]
        // );
        // Articulo::create(
        //     [
        //         'cod_producto' => 'E-100',
        //         'cod_proveedor' => 'P-100',
        //         'cod_ean' => '1234567',
        //         'nombre' => 'Limpieza de Celulares',
        //         'costo_compra' => 0,
        //         'costo_unitario' => 300,
        //         'costo_mayorista' => 300,
        //         'costo_preferencial' => 300,
        //         'stock_minimo' => 0,
        //         'tipo_producto' => 'Producto Servicio',
        //         'descripcion' => 'Realizacion de limpieza de celulares',
        //         'estado' => 1,
        //         'id_categoria' => 2
        //     ]
        // );
        // Articulo::create(
        //     [
        //         'cod_producto' => 'E-100',
        //         'cod_proveedor' => 'P-100',
        //         'cod_ean' => '1234567',
        //         'nombre' => 'Cambio de Pantallas',
        //         'costo_compra' => 0,
        //         'costo_unitario' => 500,
        //         'costo_mayorista' => 500,
        //         'costo_preferencial' => 500,
        //         'stock_minimo' => 0,
        //         'tipo_producto' => 'Producto Servicio',
        //         'descripcion' => 'Realizacion de cambio de pantalla',
        //         'estado' => 1,
        //         'id_categoria' => 2
        //     ]
        // );
    }
}
