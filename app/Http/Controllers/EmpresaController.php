<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function admin_edit( $id )
    {
        $empresa = Empresa::find( $id );
        return view('administrador.empresas.edit', [
            'empresa' => $empresa,
        ]);
    }

    public function admin_update( $id, Request $request )
    {
        Validator::make($request->all(), [
            'nombre' => 'required|string',
        ])->validate();


        $empresa = Empresa::find( $id );
        $empresa->nombre = $request->get('nombre');
        $empresa->update();


        return redirect()->back()->with('success', "La empresa {$empresa->nombre} se actualizó correctamente.");
    }

    public function admin_store( Request $request )
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
        ])->validate();

        $new_empresa = new Empresa();
        $new_empresa->nombre = $request->get('name');
        $new_empresa->save();

        return redirect()->back()->with('success', "La empresa {$new_empresa->nombre} se registró correctamente.");
    }

    public function admin_show($id)
    {
        $empresa = Empresa::where('id', $id)->with('razones_sociales')->first();
        
        return view('administrador.empresas.show', [
            "empresa" => $empresa,
        ]);
    }

    public function admin_destroy( $id )
    {
        Validator::make(['id' => $id], [
            'id' => 'required|exists:empresas,id',
        ])->validate();
        $empresa = Empresa::find( $id );
        foreach ($empresa->razones_sociales as $razon_social) {
            $razon_social->delete();
        }
        $empresa->delete();

        return redirect()->back()->with('success', "La empresa {$empresa->nombre} se eliminó correctamente y todas sus razones sociales relacionadas.");
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
