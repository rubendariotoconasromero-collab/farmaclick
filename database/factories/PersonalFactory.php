<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalFactory extends Factory
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
            'telefono' => $this->faker->numerify('7#######'),
            'direccion' => $this->faker->address(),
            'descripcion' => $this->faker->text(),
            'estado' => 1,
            'id_cargo' => \App\Models\Cargo::inRandomOrder()->first()->id,
        ];
    }
}
