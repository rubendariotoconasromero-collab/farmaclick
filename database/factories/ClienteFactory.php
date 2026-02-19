<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
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
            'matricula' => $this->faker->unique()->numerify('####'),
            'telefono' => $this->faker->numerify('7#######'),
            'direccion' => $this->faker->address(),
            'descripcion' => $this->faker->text(),
            'descuento' => $this->faker->numberBetween(1, 3),
            'estado' => 1,
        ];
    }
}
