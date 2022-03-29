<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Empresa;
use App\Models\Gestion;

class FacturaController extends Controller
{
    public function cliente_index()
    {
        $empresa = Empresa::find( auth()->user()->empresa->id );

        $gestiones = Gestion::distinct('gestion')
                                ->whereNotNull('monto_a_facturar')
                                ->whereNull('montos_facturados')
                                ->where('origin', "ST")
                                ->whereHas('razon_social', function($q) use ($empresa){
                                    $q->where('empresa_id', $empresa->id);
                                })
                                ->pluck('gestion');

        $motivos = Gestion::distinct('motivo')
                                ->whereNotNull('monto_a_facturar')
                                ->whereNull('montos_facturados')
                                ->where('origin', "ST")
                                ->whereHas('razon_social', function($q) use ($empresa){
                                    $q->where('empresa_id', $empresa->id);
                                })
                                ->pluck('motivo');

        return view('cliente.facturas.index', [
            'razones_sociales' => $empresa->razones_sociales,
            'gestiones'        => $gestiones,
            'motivos'          => $motivos,
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
