<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Empresa;

class FacturaController extends Controller
{
    public function cliente_index()
    {
        $empresa = Empresa::find( auth()->user()->empresa->id );

        return view('cliente.facturas.index', [
            'razones_sociales' => $empresa->razones_sociales,
        ]);
    }

    public function cliente_show($id)
    {
        $relations = ['gestiones', 'gestiones.razon_social', 'gestiones.razon_social.empresa'];
        $factura = Factura::where('id', $id)->with($relations)->first();
        
        return view('cliente.facturas.show', [
            "factura" => $factura,
        ]);
    }

    // CONSULTOR
    public function consultor_index()
    {
        $empresa = Empresa::find( auth()->user()->empresa->id );

        return view('consultor.facturas.index', [
            'razones_sociales' => $empresa->razones_sociales,
        ]);
    }

}
