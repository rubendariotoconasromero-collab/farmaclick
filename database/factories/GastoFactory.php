<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GastoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fecha' => $this->faker->dateTime(),
            'monto' => $this->faker->randomFloat(2,0,300),
            'descripcion' => $this->faker->text(),
            'id_motivo_gasto' => \App\Models\MotivoGasto::inRandomOrder()->first()->id,
        ];
    }
}
