<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    public function definition()
    {
        $status = [
            "Pendiente",
            "Pagada",
        ];
        return [
            "monto"         => $this->faker->numberBetween(20000,60000), 
            "fecha_de_pago" => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-10 years', $timezone=null),
            "status"        => $this->faker->randomElement( $status ),
        ];
    }
}
