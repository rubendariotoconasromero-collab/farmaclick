<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticuloServicioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cod_producto' => $this->faker->unique()->numerify('####'),
            'cod_proveedor' => null,
            'cod_ean' => null,
            'nombre' => $this->faker->words(3, true),
            'marca' => null,
            'costo_compra' => 0,
            'costo_unitario' => $this->faker->randomFloat(2),
            'costo_mayorista' => $this->faker->randomFloat(2),
            'costo_preferencial' => $this->faker->randomFloat(2),
            'stock_minimo' => 0,
            'descripcion' => $this->faker->text(),
            'tipo_producto'=> 'Producto Servicio',
            'estado' => 1,
            'id_categoria' => \App\Models\Categoria::inRandomOrder()->first()->id,
        ];
    }
}
