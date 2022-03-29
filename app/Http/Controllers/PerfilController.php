<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class PerfilController extends Controller
{
    public function cliente_show()
    {
        $user = User::find( auth()->user()->id );
        
        return view('cliente.perfil.show', [
            'user' => $user
        ]);
    }
}
