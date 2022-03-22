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
        $users = User::orderBy('name', 'ASC')->get();
        return view('administrador.usuarios.index', [
            "users" => $users,
        ]);
    }

    public function admin_create()
    {
        $empresas = Empresa::orderBy('nombre', 'ASC')->get();
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
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->rol        = $request->rol;
        $user->empresa_id = $request->empresa_id;
        $user->password   = bcrypt('12345678');
        $user->save();

        return redirect()->route('admin.usuarios.show', ['id' => $user->id])->with('success', "El usuario {$user->name} fue agregado exitosamente");
    }

    public function admin_edit($id)
    {
        if ($id == 1) {
            return redirect()->back()->with('error','El administrador no se puede editar ');
        }
        $user = User::find( $id );
        $empresas = Empresa::doesnthave('representante')->get();
        return view('administrador.usuarios.edit', [
            "user" => $user,
            "empresas" => $empresas
        ]);
    }

    public function admin_update( $id, Request $request )
    {   
        Validator::make($request->all(), [
            'name' => 'required|string',
            'empresa_id' =>'required|exists:empresas,id',
        ])->validate();
            
        $user = User::find( $id );
        if ($user->id === 1) {
            return redirect()->back()->with('error','El administrador no se puede editar ');
        }
        $user->name = $request->get('name');
        $user->empresa_id = $request->get('empresa_id');
        $user->update();

        return redirect()->route('admin.usuarios.index')->with('success','Actualización correctamente finalizada');
    }

    public function admin_destroy(  )
    {
        dd('admin_destroy()');
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
