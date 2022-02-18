<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        return view('usuarios.index', [
            "users" => $users,
        ]);
    }

    public function create()
    {
        $empresas = Empresa::all();
        return view('usuarios.create', [
            "empresas" => $empresas,
        ]);
    }
    public function show($id)
    {
        $user = User::where('id', $id)->with('empresa')->first();
        
        return view('usuarios.show', [
            "user" => $user,
        ]);
    }

    public function store(Request $request)
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

        return redirect()->route('usuarios.index')->with('success', "El usuario {$user->name} fue agregado exitosamente");
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('usuarios.edit', [
            "user" => $user,
        ]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        dd( $request->all() );
    }
}
