<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
}
