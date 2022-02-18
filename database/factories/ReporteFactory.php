<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Gestion;

class ReporteFactory extends Factory
{
    public function definition()
    {
        return [
            "gestion_id"  => Gestion::pluck('id')[$this->faker->numberBetween(1,Gestion::count()-1)],
            "titulo"      => $this->faker->randomElement( ['Solicitud', 'Revisión', 'Aprobación'] ),
            "descripcion" => $this->faker->randomElement( ['Esperando entrega del mandato', 'Realizando auditoría', 'Esperando depósito de la factura'] ),
            "created_at"  => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-5 years', $timezone=null),
        ];
    }
}
