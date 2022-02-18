<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Empresa;
use App\Models\RazonSocial;
use Carbon\Carbon;

class RazonSocialSeeder extends Seeder
{
    public function run()
    {
        foreach (config('empresas.razones_sociales') as $NOMBRE_DE_RAZON => $VALUE) {
            $empresa_existente = Empresa::where('nombre', $VALUE['empresa'])->first();
            if ( $empresa_existente ) {
                $empresa = $empresa_existente;
            } else {
                $empresa = new Empresa();
                $empresa->nombre = $VALUE['empresa'];
                $empresa->tipo = "Cliente";
                $empresa->save();
            }
            $razon_social = new RazonSocial();
            $razon_social->empresa_id      = $empresa->id;
            $razon_social->nombre          = $NOMBRE_DE_RAZON;
            $razon_social->rut             = $VALUE['rut'];
            $razon_social->contrato        = $VALUE['contrato'];
            $razon_social->no_entity       = $VALUE['no_entity'];
            // $razon_social->date_signature  = Carbon::createFromFormat('d-m-Y' ,$VALUE['date_signature'])->format('Y-m-d H:i:s');
            $razon_social->suivi_par       = $VALUE['suivi_par'];
            $razon_social->save();
        }
    }
}
