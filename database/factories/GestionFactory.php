<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\RazonSocial;
use App\Models\Factura;

class GestionFactory extends Factory
{
    public function definition()
    {
        $tipos = [
            'Oportunidades', 'Recuperación', 'Regularizaciones'
        ];
        $motivos = [
            'Ahorro SIS', 'Exceso SIS', 'Bono Mujer Trabajadora (BMT)'
        ];
        $status = [
            'En Proceso', 'Finalizada'
        ];
        return [
            "gestor_id"           => User::pluck('id')[$this->faker->numberBetween(1,User::count()-1)],
            "razon_social_id"     => RazonSocial::pluck('id')[$this->faker->numberBetween(1,RazonSocial::count()-1)],
            "factura_id"          => Factura::pluck('id')[$this->faker->numberBetween(1,Factura::count()-1)],
            "descripcion"         => "Esta es una descripción de prueba",
            "fecha_inicio"        => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-10 years', $timezone=null),
            "fecha_cierre"        => $this->faker->dateTimeBetween($startDate='-10 years', $endDate='-1 years', $timezone=null),
            "fecha_deposito"      => $this->faker->dateTimeBetween($startDate='-15 years', $endDate='-10 years', $timezone=null),
            "honorarios_fiabilis" => $this->faker->numberBetween(10000,30000), 
            "tipo"                => $this->faker->randomElement( $tipos ),
            "motivo"              => $this->faker->randomElement( $motivos ),
            "status"              => $this->faker->randomElement( $status ),
        ];
    }
}
