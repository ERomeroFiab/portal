<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;

class FacturaController extends Controller
{
    public function cliente_index()
    {
        return view('cliente.facturas.index');
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
        $facturas = Factura::all();
        
        return view('consultor.facturas.index', [
            "facturas" => $facturas,
        ]);
    }

}
