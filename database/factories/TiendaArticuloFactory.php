<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TiendaArticuloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_articulo' => $this->faker->unique()->numberBetween(1, \App\Models\Articulo::count()),
            'id_tienda' => 1,
            'stock' => 200,
        ];
    }
}
