<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Http\Requests\StoreGestionRequest;
use App\Http\Requests\UpdateGestionRequest;

use App\Models\RazonSocial;
use App\Models\Empresa;

class GestionController extends Controller
{
    public function admin_index()
    {
        $gestiones = Gestion::all();
        
        return view('administrador.gestiones.index', [
            "gestiones" => $gestiones,
        ]);
    }

    public function admin_show($id)
    {
        $gestion = Gestion::where('id', $id)->with('reportes')->first();
        
        return view('administrador.gestiones.show', [
            "gestion" => $gestion,
        ]);
    }
    // CLIENTE
    public function cliente_index()
    {
        $empresa = Empresa::find( auth()->user()->empresa->id );

        $gestiones = Gestion::distinct('gestion')
                                ->whereHas('razon_social', function($q) use ($empresa){
                                    $q->where('empresa_id', $empresa->id);
                                })
                                ->pluck('gestion');

        $motivos = Gestion::distinct('motivo')
                                ->whereHas('razon_social', function($q) use ($empresa){
                                    $q->where('empresa_id', $empresa->id);
                                })
                                ->pluck('motivo');

        return view('cliente.gestiones.index', [
            'razones_sociales' => $empresa->razones_sociales,
            'gestiones'        => $gestiones,
            'motivos'          => $motivos,
        ]);
    }

    public function cliente_show($id)
    {
        $gestion = Gestion::where('id', $id)->with('reportes')->first();
        
        return view('cliente.gestiones.show', [
            "gestion" => $gestion,
        ]);
    }

    // CONSULTOR
    public function consultor_index()
    {
        $razones_sociales = RazonSocial::all();

        $gestiones = Gestion::distinct('gestion')->pluck('gestion');

        $motivos = Gestion::distinct('motivo')->pluck('motivo');
        
        return view('consultor.gestiones.index', [
            'razones_sociales' => $razones_sociales,
            'gestiones'        => $gestiones,
            'motivos'          => $motivos,
        ]);
    }
    public function consultor_show($id)
    {
        $gestion = Gestion::where('id', $id)->with('reportes')->first();
        
        return view('consultor.gestiones.show', [
            "gestion" => $gestion,
        ]);
    }

}
