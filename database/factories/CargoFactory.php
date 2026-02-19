<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CargoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->randomElement([
                "Vendedor", 
                "Comprador", 
                "Publicista",
                "Contador", 
                "Recursos Humanos", 
                "Marketing",
                "Cajero", 
                "Repartidor", 
                "Acomodador",
                "Supervisor", 
                "Analista", 
                "Consultor",                    
            ]),
            'descripcion' => $this->faker->text(),
            'estado' => 1,
        ];
    }
}
