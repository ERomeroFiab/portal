<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Gestion;
use App\Models\Empresa;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gestiones = [];
        $gestiones_para_el_grafico = [];
        $monto_depositado_total = null;
        $empresa = null;

        if ( Auth::check() && auth()->user()->rol === "Cliente" ) {
            $empresa = Empresa::whereHas('representante', function($q) {
                $q->where('id', auth()->user()->id);
            })->first();

            $monto_depositado_total = Gestion::whereHas('razon_social', function($q) use ($empresa){
                $q->where('empresa_id', $empresa->id);
            })->sum('monto_depositado');

            $gestiones_para_el_grafico = $this->get_gestiones_para_el_grafico( $gestiones_para_el_grafico, $empresa, $monto_depositado_total );
            // Gestiones en Silvertool
            $gestiones_names_todas = Gestion::distinct('gestion')
                                                ->whereHas('razon_social', function($q) use ($empresa){
                                                    $q->where('empresa_id', $empresa->id);
                                                })->pluck('gestion');

            foreach ($gestiones_names_todas as $key => $gestion_name) {
                $suma_total = Gestion::whereHas('razon_social', function($q) use ($empresa){
                    $q->where('empresa_id', $empresa->id);
                })->where('gestion', $gestion_name)->sum('monto_depositado');
                $suma_st    = Gestion::whereHas('razon_social', function($q) use ($empresa){
                    $q->where('empresa_id', $empresa->id);
                })->where('gestion', $gestion_name)->where('origin', 'ST')->sum('monto_depositado');
                $suma_cn    = Gestion::whereHas('razon_social', function($q) use ($empresa){
                    $q->where('empresa_id', $empresa->id);
                })->where('gestion', $gestion_name)->where('origin', 'CN')->sum('monto_depositado');
                $gestiones[$key] = [];
                $gestiones[$key]['nombre']     = $gestion_name;
                $gestiones[$key]['total']      = $this->format_to_pesos($suma_total);
                $gestiones[$key]['total_st']   = $this->format_to_pesos($suma_st);
                $gestiones[$key]['total_cn']   = $this->format_to_pesos($suma_cn);
            }

        }

        return view('home', [
            'gestiones'                 => collect($gestiones),
            'gestiones_para_el_grafico' => collect($gestiones_para_el_grafico)->toJson(),
            'monto_depositado_total'    => collect($this->format_to_pesos($monto_depositado_total))->toJson(),
            'empresa'                   => $empresa,
        ]);
        return view('home');
    }

    public function format_to_pesos( $number )
    {
        if ( $number !== null ) {
            return "$ ".number_format( round($number), 0, ",", ".");
        }
        return null;
    }

    public function get_gestiones_para_el_grafico( $gestiones, $empresa, $monto_depositado_total )
    {
        $nombres_de_gestiones = Gestion::whereHas('razon_social', function($q) use ($empresa){
            $q->where('empresa_id', $empresa->id);
        })->distinct('gestion')->pluck('gestion');

        
        foreach ($nombres_de_gestiones as $name) {
            $response = [];
            $monto = Gestion::whereHas('razon_social', function($q) use ($empresa){
                $q->where('empresa_id', $empresa->id);
            })->where('gestion', $name)->sum('monto_depositado');
            $nombre_de_gestion = $name." (".$this->format_to_pesos($monto).")";
            $monto_en_porcentaje = round((($monto * 100) / $monto_depositado_total), 0);

            array_push( $response, $nombre_de_gestion );
            array_push( $response, $monto_en_porcentaje );
            array_push( $gestiones, $response );
        }
        return $gestiones;
        
    }

    public function prueba()
    {
        dd('para hacer pruebas');
    }
}
