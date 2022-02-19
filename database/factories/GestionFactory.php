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
            'Exceso CCAF Los Héroes',
            'Exceso CCAF Los Andes',
            'Exceso CCAF La Araucana',
            'Estudio de Compensaciones',
            'Exceso Colmena',
            'Exceso Masvida',
            'Exceso Óptima',
            'Exceso Vida Tres',
            'Mora Banmédica',
            'Moras Presuntas - Fase Prejudicial',
            'Moras Presuntas - En interposición de demanda',
            'Moras Presuntas - Fase Judicial',
            'Regularización moras presuntas AFP Capital',
            'Regularización moras presuntas AFP Cuprum',
            'Regularización moras presuntas AFP Hábitat',
            'Regularización moras presuntas AFP Modelo',
            'Regularización moras presuntas AFP PlanVital',
            'Regularización moras presuntas AFP Provida',
            'Regularización moras presuntas AFC',
            'Ahorro Seguro de Cesantía',
            'Exceso Seguro Cesantía - AFC',
            'Ahorro SIS',
            'Exceso SIS',
            'SIS Capital',
            'SIS Cuprum',
            'SIS Hábitat',
            'SIS Modelo',
            'SIS PlanVital',
            'SIS Provida',
            'Bono Mujer Trabajadora (BMT)',
            'Subsidio Empleo Joven (SEJ)',
            'Subsidio Previsional Trabajadores Jóvenes (SPTJ)',
            'Plan de aprendizaje - Remuneración',
            'Plan de aprendizaje - Capacitación',
            'Bono Mujer Trabajadora (BMT)',
            'Subsidio Al Empleo (SE)',
            'Subsidio Zonas Extremas Mensual',
            'Subsidio Zonas Extremas Recuperación',
            'Ahorro Seguro de Cesantía',
            'Ahorro SIS',
            'Bono Mujer Trabajadora (BMT)',
            'Exceso Colmena',
            'Exceso Masvida',
            'Exceso Óptima',
            'Exceso Seguro Cesantía - AFC',
            'Exceso Vida Tres',
            'Exceso SIS',
            'SIS Capital',
            'SIS Cuprum',
            'SIS Hábitat',
            'SIS Modelo',
            'SIS PlanVital',
            'SIS Provida',
            'Subsidio Empleo Joven (SEJ)',
            'Subsidio Previsional Trabajadores Jóvenes (SPTJ)',
            'Subsidio Zonas Extremas Mensual',
            'Subsidio Zonas Extremas Recuperación',
            'Exceso CCAF Los Héroes',
            'Exceso CCAF Los Andes',
            'Exceso CCAF La Araucana',
            'Estudio de Compensaciones',
            'Moras Presuntas - Fase Prejudicial',
            'Moras Presuntas - En interposición de demanda',
            'Moras Presuntas - Fase Judicial',
            'Mora Banmédica',
            'Regularización moras presuntas AFP Capital',
            'Regularización moras presuntas AFP Cuprum',
            'Regularización moras presuntas AFP Hábitat',
            'Regularización moras presuntas AFP Modelo',
            'Regularización moras presuntas AFP PlanVital',
            'Regularización moras presuntas AFP Provida',
            'Regularización moras presuntas AFC',
            'Plan de aprendizaje - Remuneración',
            'Plan de aprendizaje - Capacitación',
            'Bono Mujer Trabajadora (BMT)',
            'Subsidio Al Empleo (SE)',
            'Regularización moras presuntas - AFP Habitat',
            'SIS Modelo ',
            'Exceso CCAF 18 de Septiembre',
            'Regularización moras presuntas AFP UNO',
            'Exceso Trabajos Pesados AFP Capital',
            'Exceso Trabajos Pesados AFP Habitat',
            'Exceso Trabajos Pesados AFP Plan Vital',
            'Exceso Trabajos Pesados AFP Modelo',
            'Exceso Trabajos Pesados AFP UNO',
            'Exceso Trabajos Pesados AFP Provida',
            'Exceso Trabajos Pesados AFP Cuprum',
            'Excesos Trabajos Pesados ',
            'Excesos en aportes al fondo de pensión por Licencias Médicas ',
            'Exceso fondo de pensión por Licencia Médica Capital',
            'Exceso fondo de pensión por Licencia Médica Modelo',
            'Exceso fondo de pensión por Licencia Médica Habitat',
            'Exceso fondo de pensión por Licencia Médica Cuprum',
            'Exceso fondo de pensión por Licencia Médica Provida',
            'Exceso fondo de pensión por Licencia Médica Planvital',
            'Exceso fondo de pensión por Licencia Médica UNO',
        ];
        $producto = [
            'CCAF', 
            'Estudio de Compensaciones',
            'ISAPRE',
            'PRESUNTA MORA PREVISIONAL ',
            'SEGURO CESANTIA',
            'SIS',
            'SUBSIDIOS',
            'Zona Extrema ',
        ];
        $status = [
            'Pendiente', 'Finalizada'
        ];
        $glosa = [
            'Asesoría Laboral: Recuperación de excesos AFC', 
            'Asesoría Laboral: Gestión de subsidio Bono Mujer Trabajadora, Subsdio Empleo Joven y recuperación excesos SIS Capital.',
            'Asesoría Laboral: Gestión de subsidio al empleo',
            'Asesoría Laboral: Gestión de subsidios SENCE, gestión de zonas extremas y Regularización de deudas previsionales.',
            'Asesoría Laboral: Gestión Subsidio Empleo Jóven.',
            'Asesoría Laboral: Recuperación Excesos AFP Capital.',
        ];
        $bancos = [
            "Falabella",
            "Banco Estado",
            "Santander",
            "BCI",
        ];
        $razon_social_id = RazonSocial::pluck('id')[$this->faker->numberBetween(1,RazonSocial::where('empresa_id', 1)->count()-1)];
        $factura = Factura::whereHas('gestiones', function($q) use ($razon_social_id){
            $q->where('status', 'Pendiente')->where('razon_social_id', $razon_social_id);
        })->first();
        return [
            "razon_social_id"     => $razon_social_id,
            "factura_id"          => $factura ? $factura->id : Factura::pluck('id')[$this->faker->numberBetween(1,Factura::doesntHave('gestiones')->count()-1)],
            "glosa"               => $this->faker->randomElement( $glosa ),
            "banco"               => $this->faker->randomElement( $bancos ),
            "monto_depositado"    => $this->faker->numberBetween(10000,30000), 
            "monto_gestionado"    => $this->faker->numberBetween(10000,30000), 
            "monto_aprobado"      => $this->faker->numberBetween(10000,30000), 
            "fee"                 => $this->faker->numberBetween(10,50), 
            "monto_por_facturar"  => $this->faker->numberBetween(10000,30000), 
            "fecha_inicio"        => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-10 years', $timezone=null),
            "fecha_cierre"        => $this->faker->dateTimeBetween($startDate='-10 years', $endDate='-1 years', $timezone=null),
            "fecha_deposito"      => $this->faker->dateTimeBetween($startDate='-20 years', $endDate='-10 years', $timezone=null),
            "honorarios_fiabilis" => $this->faker->numberBetween(10000,30000), 
            "tipo"                => $this->faker->randomElement( $tipos ),
            "motivo"              => $this->faker->randomElement( $motivos ),
            "producto"            => $this->faker->randomElement( $producto ),
            "status"              => $this->faker->randomElement( $status ),
        ];
    }
}
