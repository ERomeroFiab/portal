<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Gestion;
use App\Models\User;

class ReporteFactory extends Factory
{
    public function definition()
    {
        $titulos = [
            'Notificacion al cliente montos a facturar',
            'Gestiones mensuales en proceso',
            'Rechazado por cliente',
            'Presentación en la institución',
        ];
        return [
            "gestor_id"   => User::pluck('id')[$this->faker->numberBetween(1,User::where('rol', "Consultor")->count()-1)],
            "gestion_id"  => Gestion::pluck('id')[$this->faker->numberBetween(1,Gestion::count()-1)],
            "titulo"      => $this->faker->randomElement( $titulos ),
            "descripcion" => $this->faker->randomElement( ['En espera de la aprobación', 'Realizando el proceso de auditoría', 'Esperando el depósito'] ),
            "created_at"  => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-5 years', $timezone=null),
        ];
    }
}
