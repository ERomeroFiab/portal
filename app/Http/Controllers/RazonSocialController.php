<?php

namespace App\Http\Controllers;

use App\Models\RazonSocial;
use App\Http\Requests\StoreRazonSocialRequest;
use App\Http\Requests\UpdateRazonSocialRequest;

use App\Models\Empresa;
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
        $razon_social = RazonSocial::where('id', $id)->with('gestiones')->first();
        
        return view('administrador.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
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
            "empresa" => $empresa,
            "cantidad_de_razones_sociales" => $empresa->razones_sociales->count(),
        ]);
    }

    public function cliente_show($id)
    {
        $razon_social = RazonSocial::where('id', $id)->with('gestiones')->first();
        
        return view('cliente.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }

    // CONSULTOR
    public function consultor_show($id)
    {
        $razon_social = RazonSocial::where('id', $id)->with('gestiones')->first();
        
        return view('consultor.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }
}
