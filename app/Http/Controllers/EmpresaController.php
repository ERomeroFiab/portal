<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;

class EmpresaController extends Controller
{
    public function admin_index()
    {
        $empresas = Empresa::all();
        
        return view('administrador.empresas.index', [
            "empresas" => $empresas,
        ]);
    }

    public function admin_create()
    {
        return view('administrador.empresas.create');
    }

    public function admin_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('administrador.empresas.show', [
            "empresa" => $empresa,
        ]);
    }

    // CLIENTE
    public function cliente_index()
    {
        $empresas = Empresa::all();
        
        return view('administrador.empresas.index', [
            "empresas" => $empresas,
        ]);
    }

    public function cliente_create()
    {
        return view('administrador.empresas.create');
    }

    public function cliente_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('administrador.empresas.show', [
            "empresa" => $empresa,
        ]);
    }
    // CONSULTOR
    public function consultor_index()
    {
        $empresas = Empresa::all();
        
        return view('consultor.empresas.index', [
            "empresas" => $empresas,
        ]);
    }

    public function consultor_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('consultor.empresas.show', [
            "empresa" => $empresa,
        ]);
    }

}
