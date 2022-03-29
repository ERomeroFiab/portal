<?php

namespace App\Http\Controllers;

use App\Models\RazonSocial;
use App\Http\Requests\StoreRazonSocialRequest;
use App\Http\Requests\UpdateRazonSocialRequest;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RazonSocialController extends Controller
{
    public function admin_index()
    {
        $razon_social = RazonSocial::all();
        
        return view('administrador.razones-sociales.index', [
            "razon_social" => $razon_social,
        ]);
    }

    public function admin_show($id)
    {
        $relations = [
            'invoices',
            'missions',
            'missions.mission_motives',
            'missions.mission_motives.mission_motive_ecos',
            'Empresa',

            
        ];
        $razon_social = RazonSocial::where('id', $id)->with( $relations )->first();
        
        return view('administrador.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }

    public function admin_edit( $id )
    {
        $razon_social = RazonSocial::find( $id );
        return view('administrador.razones-sociales.edit', [
            'razon_social' => $razon_social,
        ]);
    }

    public function admin_update( $id, Request $request )
    {
        Validator::make($request->all(), [
            'nombre'                    => 'required|string',
            'ciudad'                    => 'required|string',
            'codigo_postal'             => 'required|string',
            'direccion'                 => 'nullable|string',
            'numero_de_cuenta_bancaria' => 'required|string',
            'banco'                     => 'required|string',
            'tipo_de_cuenta'            => 'required|string',
        ])->validate();


        $razon_social = RazonSocial::find( $id );
        $razon_social->nombre                    = $request->get('nombre');
        $razon_social->ciudad                    = $request->get('ciudad');
        $razon_social->codigo_postal             = $request->get('codigo_postal');
        $razon_social->direccion                 = $request->get('direccion');
        $razon_social->numero_de_cuenta_bancaria = $request->get('numero_de_cuenta_bancaria');
        $razon_social->banco                     = $request->get('banco');
        $razon_social->tipo_de_cuenta            = $request->get('tipo_de_cuenta');
        $razon_social->update();


        return redirect()->back()->with('success', "Actualización correctamente finalizada");
    }

    public function admin_resetear_password( $id )
    {   
        $razon_social= RazonSocial::find($id);
        $razon_social->empresa->representante->password = bcrypt($razon_social->rut);
        $razon_social->empresa->representante->update();
        return redirect()->back()->with('success', "La contraseña se a reseteado correctamente.");
    }

    
    public function admin_destroy( $id )
    {
        Validator::make(['id' => $id], [
            'id' =>'required|exists:razon_socials,id',
        ])->validate();

        $razon_social = RazonSocial::find( $id );
        $user_id = $razon_social->empresa->representante->id;
        $razon_social->delete();

        return redirect()->route('admin.usuarios.show', ['id' => $user_id])->with('success', "Razón social {$razon_social->nombre} eliminada correctamente");
    }

    // CLIENTE
    public function cliente_index()
    {
        $user_id = auth()->user()->id;
        $empresa = Empresa::whereHas('representante', function ($q) use ($user_id){
            $q->where('id', $user_id);
        })->first();
        
        return view('cliente.razones-sociales.index', [
            "razones_sociales" => $empresa->razones_sociales,
        ]);
    }

    public function cliente_show($id)
    {
        $razon_social = RazonSocial::where('id', $id)->first();
        
        return view('cliente.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }

    // CONSULTOR
    public function consultor_show($id)
    {
        $razon_social = RazonSocial::where('id', $id);
        
        return view('consultor.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }
}
