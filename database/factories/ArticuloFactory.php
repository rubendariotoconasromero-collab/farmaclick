<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticuloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tipo_producto = $this->faker->randomElement([
                'Producto Venta', 
                'Producto Servicio', 
            ]);
        $cod_producto = $tipo_producto == 'Producto Venta' ? $this->faker->unique()->numerify('####') : null;
        $cod_ean = $tipo_producto == 'Producto Venta' ? $this->faker->unique()->numerify('############') : null;
        $costo_compra = $tipo_producto == 'Producto Venta' ? $this->faker->randomFloat(2, 0, 100) : 0;
        $stock_minimo = $tipo_producto == 'Producto Venta' ? $this->faker->numberBetween(1, 500) : 0;
        return [
            'tipo_producto'=> $tipo_producto,
            'cod_producto' => $this->faker->unique()->numerify('####'),
            'cod_proveedor' => $cod_producto,
            'cod_ean' => $cod_ean,
            'nombre' => $this->faker->words(3, true),
            'marca' => $this->faker->word(),
            'costo_compra' => $costo_compra,
            'costo_unitario' => $this->faker->randomFloat(2,100,150),
            'costo_mayorista' => $this->faker->randomFloat(2,150,200),
            'costo_preferencial' => $this->faker->randomFloat(2,200,250),
            'stock_minimo' => $stock_minimo,
            'descripcion' => $this->faker->text(),
            'estado' => 1,
            'id_categoria' => \App\Models\Categoria::inRandomOrder()->first()->id,
        ];
    }
}
