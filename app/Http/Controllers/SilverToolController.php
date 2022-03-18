<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\Empresa;
use App\Models\RazonSocial;
use Illuminate\Support\Facades\Validator;

class SilverToolController extends Controller
{
    public function actualizar_group_names()
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

    public function send_data_to_api_in_silver( $url, $request, $method )
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Request-With',
            'Access-Control-Allow-Credentials' => 'true',
            'CUSTOM' => config('services.TOKEN_FOR_REQUESTS_TO_SILVER'),
        ])->{$method}($url, $request);
        
        return $response->json();

    }

    public function actualizar_razones_sociales_y_empresas()
    {
        $response = $this->get_datos_de_api( config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razones_sociales" );

        foreach ($response as $razon_social) {
            $empresa_existente = Empresa::where('nombre', $razon_social['GROUP'])->first();
            if ( !$empresa_existente ) {
                $empresa_existente = new Empresa();
                $empresa_existente->nombre = $razon_social['GROUP'];
                $empresa_existente->save();
            }
        }

        return "Las razones sociales fueron actualizadas correctamente";
    }

    public function get_razones_sociales_from_silvertool_by_group_name( Request $request )
    {
        $validator = Validator::make($request->all(), [
            'empresa_id'  => 'required|integer|exists:empresas,id',
        ]);

        if ( $validator->fails() ) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 400);
        }
        $empresa = Empresa::find( $request->get('empresa_id') );

        $data = [
            'group_name' => $empresa->nombre,
        ];

        $response = $this->send_data_to_api_in_silver( 
            config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razones_sociales_by_group_name",
            $data,
            "get",
        );
        
        foreach ($response as $razon_social) {
            $razon_social_existente = RazonSocial::where('rut', $razon_social['SIRET'])->first();
            if ( $razon_social_existente ) {
                continue;
            }
            $nueva_razon_social = new RazonSocial();
            $nueva_razon_social->empresa_id                 = $empresa->id;
            $nueva_razon_social->nombre                     = $razon_social['RAISON_SOC'];
            $nueva_razon_social->rut                        = $razon_social['SIRET'];
            $nueva_razon_social->ciudad                     = $razon_social['VILLE'];
            $nueva_razon_social->codigo_postal              = $razon_social['CODE_POSTAL'];
            $nueva_razon_social->save();
        }

        return redirect()->back()->with('success', "Las razones sociales se actualizaron correctamente");
    }

    public function get_razon_social_by_rut( Request $request )
    {
        $validator = Validator::make($request->all(), [
            'razon_social_id'  => 'required|integer|exists:razon_socials,id',
        ]);

        if ( $validator->fails() ) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $razon_social = razonSocial::find( $request->get('razon_social_id') );

        $data = [
            'rut' => $razon_social->rut,
        ];

        $response = $this->send_data_to_api_in_silver( 
            config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razon_social_by_rut",
            $data,
            "get",
        );

        dd( $response );
    }
}
