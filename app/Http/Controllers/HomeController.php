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
        $empresa = null;
        if ( Auth::check() ) {
            $empresa = Empresa::whereHas('representante', function($q) {
                $q->where('id', auth()->user()->id);
            })->first();
            // Gestiones en Silvertool
            $gestiones_names_todas = Gestion::distinct('gestion')
                                                ->whereHas('razon_social', function($q) use ($empresa){
                                                    $q->where('empresa_id', $empresa->id);
                                                })->pluck('gestion');

            foreach ($gestiones_names_todas as $key => $gestion_name) {
                $suma_total = Gestion::where('gestion', $gestion_name)->sum('monto_depositado');
                $suma_st    = Gestion::where('gestion', $gestion_name)->where('origin', 'ST')->sum('monto_depositado');
                $suma_cn    = Gestion::where('gestion', $gestion_name)->where('origin', 'CN')->sum('monto_depositado');
                $gestiones[$key] = [];
                $gestiones[$key]['nombre']     = $gestion_name;
                $gestiones[$key]['total']      = $this->format_to_pesos($suma_total);
                $gestiones[$key]['total_st']   = $this->format_to_pesos($suma_st);
                $gestiones[$key]['total_cn']   = $this->format_to_pesos($suma_cn);
            }

        }


        return view('home', [
            'gestiones' => collect($gestiones),
            'empresa'   => $empresa,
        ]);
    }

    public function format_to_pesos( $number )
    {
        if ( $number !== null ) {
            return "$ ".number_format( round($number), 0, ",", ".");
        }
        return null;
    }

    public function prueba()
    {
        dd('para hacer pruebas');
    }
}
