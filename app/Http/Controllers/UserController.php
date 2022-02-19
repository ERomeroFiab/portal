<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function admin_index()
    {
        $users = User::all();
        
        return view('administrador.usuarios.index', [
            "users" => $users,
        ]);
    }

    public function admin_create()
    {
        $empresas = Empresa::all();
        return view('administrador.usuarios.create', [
            "empresas" => $empresas,
        ]);
    }
    public function admin_show($id)
    {
        $user = User::where('id', $id)->with('empresa')->first();
        
        return view('administrador.usuarios.show', [
            "user" => $user,
        ]);
    }

    public function admin_store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
        ])->validate();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->empresa_id = $request->empresa_id;
        $user->password = bcrypt('12345678');
        $user->save();

        return redirect()->route('admin.usuarios.show', ['id' => $user->id])->with('success', "El usuario {$user->name} fue agregado exitosamente");
    }

    public function admin_edit($id)
    {
        $user = User::find($id);
        return view('administrador.usuarios.edit', [
            "user" => $user,
        ]);
    }

    public function admin_update($id, Request $request)
    {
        $user = User::find($id);
        dd( $request->all() );
    }
    // CONSULTOR
    public function consultor_show($id)
    {
        $user = User::where('id', $id)->with('empresa')->first();
        
        return view('consultor.usuarios.show', [
            "user" => $user,
        ]);
    }
}
