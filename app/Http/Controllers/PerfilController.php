<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function cliente_show()
    {
        $user = User::find( auth()->user()->id );
        
        return view('cliente.perfil.show', [
            'user' => $user
        ]);
    }

    public function perfil_update( $id, Request $request )
    {
        
        Validator::make($request->all(), [
            'password' => 'required|string',
        ])->validate();
            

        $user = User::find( $id );
        $user->password = bcrypt($request->get('password'));
        $user->update();
        return redirect()->back()->with('success', "La contraseña de  {$user->name} se actualizó correctamente.");
    }

    public function first_login()
    {
        return view('cliente.perfil.first_login');
    }

    public function change_password_first_time( Request $request )
    {
        Validator::make($request->all(), [
            'email'           => 'required|email',
            'password'        => ['required', Password::min(8)],
        ])->validate();

        $user = User::find( auth()->user()->id );
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->first_login = now();
        $user->update();

        return redirect()->route('home')->with('success', "La contraseña se actualizó correctamente.");
    }
}
