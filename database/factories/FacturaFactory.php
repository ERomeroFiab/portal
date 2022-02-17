<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    public function definition()
    {
        return [
            "monto" => $this->faker->numberBetween(20000,60000), 
            "status" => $this->faker->randomElement( ['Pendiente', 'En Proceso', 'Pagada'] ),
        ];
    }
}
