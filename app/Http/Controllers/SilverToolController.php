<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\Empresa;
use App\Models\RazonSocial;

class SilverToolController extends Controller
{
    public function actualizar_database()
    {
        $this->actualizar_razones_sociales_y_empresas();

        return back()->with('success', "Base de datos actualizada correctamente.");
            
    }

    public function get_datos_de_api( $url )
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Request-With',
            'Access-Control-Allow-Credentials' => 'true',
            'CUSTOM' => config('services.TOKEN_FOR_REQUESTS_TO_SILVER'),
        ])->get($url);
        
        if ( !$response->ok() ) {
            return null;
        } 

        return $response->json();

    }

    public function actualizar_razones_sociales_y_empresas()
    {
        $data = $this->get_datos_de_api( config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razones_sociales" );
        if ( $data === null ) {
            // handle error
            return;
        }

        foreach ($data as $razon_social) {
            $razon_social_existente = RazonSocial::where('rut', $razon_social['SIRET'])->first();
            if ( !$razon_social_existente ) {
                $empresa_existente = Empresa::where('nombre', $razon_social['GROUP'])->first();
                if ( !$empresa_existente ) {
                    $empresa_existente = new Empresa();
                    $empresa_existente->nombre = $razon_social['GROUP'];
                    $empresa_existente->tipo   = $razon_social['TYPE_FICHE'];
                    $empresa_existente->save();
                }
                $RAZON = new RazonSocial();
                $RAZON->empresa_id    = $empresa_existente->id;
                $RAZON->rut           = $razon_social['SIRET'];
                $RAZON->nombre        = $razon_social['RAISON_SOC'];
                $RAZON->tipo          = $razon_social['TYPE_FICHE'];
                $RAZON->codigo_postal = $razon_social['CODE_POSTAL'];
                foreach (config('razones_sociales') as $RUT => $value) {
                    if ( $RAZON->rut === $RUT  ) {
                        $RAZON->numero_de_cuenta_bancaria = intval(preg_replace("/[-\s]/i", "", $value['numero']));
                        $RAZON->banco                     = $value['banco'];
                        $RAZON->tipo_de_cuenta            = "Corriente";

                        
                    }
                }
                $RAZON->save();
            }
        }
        return "Las razones sociales fueron actualizadas correctamente";
    }
}
