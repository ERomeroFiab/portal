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

    public function admin_store(StoreEmpresaRequest $request)
    {
        //
    }

    public function admin_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('administrador.empresas.show', [
            "empresa" => $empresa,
        ]);
    }

    public function admin_edit(Empresa $empresa)
    {
        //
    }

    public function admin_update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        //
    }

    public function admin_destroy(Empresa $empresa)
    {
        //
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

    public function cliente_store(StoreEmpresaRequest $request)
    {
        //
    }

    public function cliente_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('administrador.empresas.show', [
            "empresa" => $empresa,
        ]);
    }

    public function cliente_edit(Empresa $empresa)
    {
        //
    }

    public function cliente_update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        //
    }

    public function cliente_destroy(Empresa $empresa)
    {
        //
    }
}
