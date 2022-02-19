<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;

class FacturaController extends Controller
{
    public function cliente_index()
    {
        $empresa_id = auth()->user()->empresa_id;
        $facturas = Factura::where('status', "Pendiente")->whereHas('gestiones', function($q) use ($empresa_id) {
                                $q->whereHas('razon_social', function($q2) use ($empresa_id) {
                                    $q2->whereHas('empresa', function($q3) use ($empresa_id){
                                        $q3->where('id', $empresa_id);
                                    });
                                });
                            })->get();
        
        return view('cliente.facturas.index', [
            "facturas" => $facturas,
            "cantidad_de_facturas" => $facturas->count(),
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


}
