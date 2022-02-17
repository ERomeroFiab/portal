<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    public function definition()
    {
        return [
            "nombre" => $this->faker->randomElement( ['Wallmart', 'Cocacola', 'Falabella'] ),
            "tipo"   => $this->faker->randomElement( ['Prospecto', 'Cliente'] ),
            "status" => null,
        ];
    }
}
