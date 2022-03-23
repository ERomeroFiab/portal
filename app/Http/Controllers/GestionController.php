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
        return view('cliente.gestiones.index');
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
        $gestiones = Gestion::all();
        
        return view('consultor.gestiones.index', [
            "gestiones" => $gestiones,
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
