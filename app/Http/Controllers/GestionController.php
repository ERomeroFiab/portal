<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Http\Requests\StoreGestionRequest;
use App\Http\Requests\UpdateGestionRequest;

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
        $cliente = auth()->user();
        $empresa_id = $cliente->empresa_id;
        $gestiones = Gestion::whereHas('razon_social', function($q) use ($empresa_id){
            $q->where('empresa_id', $empresa_id);
        })->get();
        
        return view('cliente.gestiones.index', [
            "gestiones" => $gestiones,
            "cantidad_de_gestiones" => $gestiones->count(),
        ]);
    }

    public function cliente_show($id)
    {
        $gestion = Gestion::where('id', $id)->with('reportes')->first();
        
        return view('cliente.gestiones.show', [
            "gestion" => $gestion,
        ]);
    }

}
