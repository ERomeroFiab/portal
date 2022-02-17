<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Empresa;

class RazonSocialFactory extends Factory
{
    public function definition()
    {
        return [
            "empresa_id" => Empresa::pluck('id')[$this->faker->numberBetween(1,Empresa::count()-1)],
            "nombre"     => $this->faker->randomElement( ['Wallmart Repartidor', 'Cocacola Transporte', 'Falabella Repartidor'] ),
            "rut"        => $this->faker->numberBetween(10000000,27000000)."-".$this->faker->numberBetween(1,9), 
            "direccion"  => $this->faker->address(),
            "status"     => null,
        ];
    }
}
