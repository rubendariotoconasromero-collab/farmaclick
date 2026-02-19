<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'nit' => $this->faker->unique()->numerify('####'),
            'contacto' => $this->faker->name(),
            'telefono' => $this->faker->numerify('7#######'),
            'direccion' => $this->faker->address(),
            'descripcion' => $this->faker->text(),
            'estado' => 1,
        ];
    }
}
