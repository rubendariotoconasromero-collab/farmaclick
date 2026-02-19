<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
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
                "Celular", 
                "Computadora", 
                "Pantalla",
                "Auricular", 
                "Bateria", 
                "Parlante",
                "Microfono", 
                "Flex", 
                "Consensador",
                "Chip", 
                "Resistencia", 
                "Fusible",           
                "Cinta Adhesiva", 
                "Destornillador", 
                "Tornillo",          
            ]),
            'descripcion' => $this->faker->text(),
            'estado' => 1,
        ];
    }
}
