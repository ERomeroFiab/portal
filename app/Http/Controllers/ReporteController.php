<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Http\Requests\StoreReporteRequest;
use App\Http\Requests\UpdateReporteRequest;

class ReporteController extends Controller
{
    public function admin_index()
    {
        $reportes = Reporte::all();
        
        return view('administrador.reportes.index', [
            "reportes" => $reportes,
        ]);
    }

    public function admin_show($id)
    {
        $reporte = Reporte::where('id', $id)->first();
        
        return view('administrador.reportes.show', [
            "reporte" => $reporte,
        ]);
    }

    // CONSULTOR
    public function consultor_show($id)
    {
        $reporte = Reporte::where('id', $id)->first();
        
        return view('consultor.reportes.show', [
            "reporte" => $reporte,
        ]);
    }


}
